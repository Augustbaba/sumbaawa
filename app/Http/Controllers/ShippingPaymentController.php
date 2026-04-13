<?php

namespace App\Http\Controllers;

use App\Mail\ShippingPaymentConfirmationEmail;
use App\Models\Commande;
use App\Models\Currency;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class ShippingPaymentController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  Afficher la page de paiement
    // ─────────────────────────────────────────────────────────────────────────

    public function showPayment($commandeCode)
    {
        $commande = Commande::where('code', $commandeCode)
            ->where('user_id', Auth::id())
            ->where('status', 'ready_pickup')
            ->whereNotNull('shipping_fee')
            ->where('shipping_status', 'fee_pending')
            ->firstOrFail();

        return view('front.pages.shipping-payment', compact('commande'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  Helpers conversion
    // ─────────────────────────────────────────────────────────────────────────

    private function convertToUSD($amount, $fromCurrency = 'XOF')
    {
        if ($fromCurrency === 'USD') return round($amount, 2);

        $usdCurrency = Currency::where('code', 'USD')->first();
        if (!$usdCurrency) {
            Log::warning('Devise USD non trouvée, taux par défaut');
            return round($amount / 600, 2);
        }

        $usdRate = $usdCurrency->exchange_rate;

        if ($fromCurrency === 'XOF') {
            return round($amount / $usdRate, 2);
        }

        $fromCurrencyObj = Currency::where('code', $fromCurrency)->first();
        if (!$fromCurrencyObj) return round($amount / $usdRate, 2);

        return round(($amount * $fromCurrencyObj->exchange_rate) / $usdRate, 2);
    }

    private function convertUSDToXOF($amountUSD)
    {
        $usdCurrency = Currency::where('code', 'USD')->first();
        if (!$usdCurrency) return round($amountUSD * 600, 2);
        return round($amountUSD * $usdCurrency->exchange_rate, 2);
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  Helper provider PayPal
    // ─────────────────────────────────────────────────────────────────────────

    private function getPayPalProvider(): PayPalClient
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials([
            'mode'    => config('services.paypal.mode', 'sandbox'),
            'sandbox' => [
                'client_id'     => config('services.paypal.sandbox.client_id'),
                'client_secret' => config('services.paypal.sandbox.client_secret'),
                'app_id'        => 'APP-80W284485P519543T',
            ],
            'live' => [
                'client_id'     => config('services.paypal.live.client_id'),
                'client_secret' => config('services.paypal.live.client_secret'),
                'app_id'        => config('services.paypal.live.app_id'),
            ],
            'payment_action' => 'Sale',
            'currency'       => 'USD',
            'notify_url'     => '',
            'locale'         => 'en_US',
            'validate_ssl'   => true,
        ]);
        $provider->getAccessToken();
        return $provider;
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  Helper — récupérer et valider la commande
    // ─────────────────────────────────────────────────────────────────────────

    private function getValidCommande(int $commandeId): Commande
    {
        return Commande::where('id', $commandeId)
            ->where('user_id', Auth::id())
            ->where('status', 'ready_pickup')
            ->whereNotNull('shipping_fee')
            ->where('shipping_status', 'fee_pending')
            ->firstOrFail();
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  Helper — marquer la commande comme payée et notifier
    // ─────────────────────────────────────────────────────────────────────────

    private function markShippingPaid(
        Commande $commande,
        string   $paymentId,
        string   $paymentMethod,
    ): void {
        $commande->shipping_status         = 'fee_paid';
        $commande->shipping_payment_id     = $paymentId;
        $commande->shipping_payment_method = $paymentMethod;
        $commande->shipping_payment_date   = now();
        $commande->save();

        try {
            $this->notificationService->notifyShippingPaymentSuccess($commande);
        } catch (\Exception $e) {
            Log::error('Erreur notif shipping payment: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  POST /shipping/paypal/create
    // ─────────────────────────────────────────────────────────────────────────

    public function createPayPalOrder(Request $request)
    {
        try {
            Log::info('Création commande PayPal frais livraison', $request->all());

            $request->validate([
                'commande_id' => 'required|exists:commandes,id',
                'amount'      => 'required|numeric|min:0.01',
                'currency'    => 'nullable|string',
                'amountInXOF' => 'nullable|numeric',
            ]);

            $commande    = $this->getValidCommande((int) $request->commande_id);
            $amountXOF   = $request->amountInXOF ?? $commande->shipping_fee;
            $amountUSD   = $request->amount;
            $currency    = $request->currency ?? 'XOF';

            // Vérifier cohérence montant vs frais enregistrés (1% tolérance)
            $tolerance = $commande->shipping_fee * 0.01;
            if (abs($amountXOF - $commande->shipping_fee) > $tolerance) {
                Log::warning('Écart frais livraison createPayPal', [
                    'frais_enregistres' => $commande->shipping_fee,
                    'montant_fourni'    => $amountXOF,
                ]);
                $amountXOF = $commande->shipping_fee;
            }

            // Recalculer USD depuis XOF (source de vérité serveur)
            $calculatedUSD = $this->convertToUSD($amountXOF, 'XOF');
            $usdTolerance  = $calculatedUSD * 0.01;
            if (abs($calculatedUSD - $amountUSD) > $usdTolerance) {
                Log::warning('Écart conversion USD frais livraison', [
                    'fourni_usd'    => $amountUSD,
                    'calcule_usd'   => $calculatedUSD,
                    'montant_xof'   => $amountXOF,
                ]);
                $amountUSD = $calculatedUSD;
            }

            $amountUSD = round($amountUSD, 2);

            if ($amountUSD < 0.01) {
                return response()->json([
                    'error' => 'Montant trop faible pour PayPal (minimum $0.01)',
                ], 400);
            }

            $provider = $this->getPayPalProvider();

            $order = $provider->createOrder([
                'intent'         => 'CAPTURE',
                'purchase_units' => [[
                    'amount'      => [
                        'currency_code' => 'USD',
                        'value'         => number_format($amountUSD, 2, '.', ''),
                    ],
                    'description' => 'Frais de livraison — Commande #' . $commande->code,
                    'custom_id'   => json_encode([
                        'commande_id' => $commande->id,
                        'amount_xof'  => $amountXOF,
                        'currency'    => $currency,
                        'type'        => 'shipping_fee',
                    ]),
                ]],
                'application_context' => [
                    'cancel_url' => route('shipping.payment.cancel', $commande->id),
                    'return_url' => route('shipping.payment.success'),
                ],
            ]);

            if (!isset($order['id'])) {
                Log::error('Erreur création commande PayPal frais livraison', $order);
                return response()->json([
                    'error' => 'Erreur lors de la création du paiement',
                ], 500);
            }

            // Stocker en session pour la capture
            session([
                'shipping_payment_order_id' => $order['id'],
                'shipping_commande_id'      => $commande->id,
                'shipping_amount_usd'       => $amountUSD,
                'shipping_amount_xof'       => $amountXOF,
                'shipping_currency'         => $currency,
            ]);

            Log::info('Commande PayPal frais livraison créée', [
                'order_id'    => $order['id'],
                'commande_id' => $commande->id,
                'amount_usd'  => $amountUSD,
                'amount_xof'  => $amountXOF,
            ]);

            return response()->json(['success' => true, 'orderID' => $order['id']]);

        } catch (\Exception $e) {
            Log::error('Exception createShippingPayPalOrder: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Erreur serveur : ' . $e->getMessage()], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  POST /shipping/paypal/capture
    // ─────────────────────────────────────────────────────────────────────────

    public function capturePayPalOrder(Request $request)
    {
        try {
            Log::info('Capture PayPal frais livraison', $request->all());

            $request->validate([
                'orderID'     => 'required',
                'currency'    => 'nullable|string',
                'amountInXOF' => 'nullable|numeric',
            ]);

            $commandeId = session('shipping_commande_id');
            if (!$commandeId) {
                return response()->json(['success' => false, 'error' => 'Session expirée'], 400);
            }

            $commande = $this->getValidCommande((int) $commandeId);
            $provider = $this->getPayPalProvider();
            $result   = $provider->capturePaymentOrder($request->orderID);

            Log::info('Résultat capture PayPal frais livraison', [
                'status' => $result['status'] ?? 'unknown',
            ]);

            if (($result['status'] ?? '') !== 'COMPLETED') {
                Log::error('Capture PayPal frais livraison non COMPLETED', $result);
                return response()->json([
                    'success' => false,
                    'error'   => 'Le paiement n\'a pas pu être capturé',
                ], 400);
            }

            // Montant réellement capturé par PayPal (source de vérité)
            $paypalAmountUSD = (float) (
                $result['purchase_units'][0]['payments']['captures'][0]['amount']['value'] ?? 0
            );
            $sessionAmountUSD = session('shipping_amount_usd', 0);
            $amountXOF        = session('shipping_amount_xof', $commande->shipping_fee);
            $currency         = $request->currency ?? session('shipping_currency', 'XOF');

            // Vérification écart PayPal vs session
            if ($paypalAmountUSD > 0 && abs($paypalAmountUSD - $sessionAmountUSD) > 0.02) {
                Log::warning('Écart PayPal vs session frais livraison', [
                    'session_usd' => $sessionAmountUSD,
                    'paypal_usd'  => $paypalAmountUSD,
                ]);
                $amountXOF = $this->convertUSDToXOF($paypalAmountUSD);
            }

            // Vérification cohérence vs frais enregistrés (2% tolérance)
            $tolerance = $commande->shipping_fee * 0.02;
            if (abs($amountXOF - $commande->shipping_fee) > $tolerance) {
                Log::warning('Écart frais livraison capture PayPal', [
                    'frais_commande' => $commande->shipping_fee,
                    'montant_paye'   => $amountXOF,
                ]);
            }

            $this->markShippingPaid($commande, $result['id'], 'paypal');

            // Nettoyer la session
            session()->forget([
                'shipping_payment_order_id',
                'shipping_commande_id',
                'shipping_amount_usd',
                'shipping_amount_xof',
                'shipping_currency',
            ]);

            Log::info('Frais livraison PayPal payés', [
                'commande_id' => $commande->id,
                'amount_xof'  => $amountXOF,
                'amount_usd'  => $paypalAmountUSD,
            ]);

            return response()->json([
                'success'     => true,
                'message'     => 'Paiement des frais de livraison réussi',
                'commande_id' => $commande->id,
                'amount_xof'  => $amountXOF,
                'amount_usd'  => $paypalAmountUSD,
                'currency'    => $currency,
            ]);

        } catch (\Exception $e) {
            Log::error('Exception captureShippingPayPalOrder: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'error'   => 'Erreur lors du traitement : ' . $e->getMessage(),
            ], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  POST /shipping/wallet/pay  ← NOUVEAU
    // ─────────────────────────────────────────────────────────────────────────

    public function payWithWallet(Request $request)
    {
        try {
            Log::info('Paiement frais livraison portefeuille', $request->all());

            $request->validate([
                'commande_id' => 'required|exists:commandes,id',
                'amount_xof'  => 'required|numeric|min:1',
            ]);

            $user     = Auth::user();
            $commande = $this->getValidCommande((int) $request->commande_id);

            // ── Vérifier cohérence montant vs frais enregistrés (1% tolérance) ──
            $amountXOF = (float) $request->amount_xof;
            $tolerance = $commande->shipping_fee * 0.01;
            if (abs($amountXOF - $commande->shipping_fee) > $tolerance) {
                Log::warning('Wallet frais livraison: écart montant', [
                    'frais_enregistres' => $commande->shipping_fee,
                    'montant_fourni'    => $amountXOF,
                ]);
                // On utilise les frais enregistrés (source de vérité serveur)
                $amountXOF = $commande->shipping_fee;
            }

            // ── Vérifier le solde ───────────────────────────────────────────
            if ($user->solde < $amountXOF) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Solde insuffisant. Votre solde est de '
                                 . number_format($user->solde, 0, ',', ' ') . ' XOF.',
                ], 400);
            }

            // ── Défalquer le solde (atomique) ───────────────────────────────
            $user->decrement('solde', $amountXOF);

            // ── Identifiant de transaction portefeuille ─────────────────────
            $walletTransactionId = 'WALLET-SHIP-' . $user->id . '-' . now()->format('YmdHis');

            // ── Marquer la commande comme payée ─────────────────────────────
            $this->markShippingPaid($commande, $walletTransactionId, 'wallet');

            Log::info('Frais livraison wallet payés', [
                'commande_id' => $commande->id,
                'amount_xof'  => $amountXOF,
                'user_id'     => $user->id,
                'new_solde'   => $user->fresh()->solde,
            ]);

            return response()->json([
                'success'     => true,
                'message'     => 'Paiement des frais de livraison réussi',
                'commande_id' => $commande->id,
                'amount_xof'  => $amountXOF,
                'new_solde'   => $user->fresh()->solde,
            ]);

        } catch (\Exception $e) {
            Log::error('Exception payShippingWithWallet: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'error'   => 'Erreur serveur : ' . $e->getMessage(),
            ], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  POST /shipping/elongopay/capture
    // ─────────────────────────────────────────────────────────────────────────

    public function captureElongoPay(Request $request)
    {
        try {
            $request->validate([
                'transaction_id' => 'required|string',
                'amount_xof'     => 'required|numeric|min:1',
                'commande_id'    => 'required|exists:commandes,id',
            ]);

            $commande  = $this->getValidCommande((int) $request->commande_id);
            $tolerance = $commande->shipping_fee * 0.02;

            if (abs($request->amount_xof - $commande->shipping_fee) > $tolerance) {
                Log::warning('Écart frais livraison ElongoPay', [
                    'frais_commande' => $commande->shipping_fee,
                    'montant_recu'   => $request->amount_xof,
                ]);
            }

            $this->markShippingPaid($commande, $request->transaction_id, 'elongopay');

            Log::info('Frais livraison ElongoPay payés', [
                'commande_id'    => $commande->id,
                'transaction_id' => $request->transaction_id,
                'amount_xof'     => $request->amount_xof,
            ]);

            return response()->json([
                'success'     => true,
                'message'     => 'Paiement des frais de livraison réussi',
                'commande_id' => $commande->id,
                'amount_xof'  => $commande->shipping_fee,
            ]);

        } catch (\Exception $e) {
            Log::error('Exception captureElongoPayShipping: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'error'   => 'Erreur lors du traitement : ' . $e->getMessage(),
            ], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  Page succès / annulation
    // ─────────────────────────────────────────────────────────────────────────

    public function success()
    {
        $commandeId = session('shipping_commande_id');

        if (!$commandeId) {
            return redirect()->route('dashboard')->with('error', 'Session expirée');
        }

        $commande = Commande::where('id', $commandeId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        session()->forget([
            'shipping_payment_order_id',
            'shipping_commande_id',
            'shipping_amount_usd',
            'shipping_amount_xof',
            'shipping_currency',
        ]);

        return view('front.pages.shipping-payment-success', compact('commande'));
    }

    public function cancel($commandeId)
    {
        session()->forget([
            'shipping_payment_order_id',
            'shipping_commande_id',
            'shipping_amount_usd',
            'shipping_amount_xof',
            'shipping_currency',
        ]);

        return redirect()->route('shipping.payment.show', $commandeId)
            ->with('error', 'Paiement annulé');
    }
}
