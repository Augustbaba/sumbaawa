<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\DeliveryProcessingEmail;
use App\Models\Commande;
use App\Models\Commander;
use App\Models\Currency;
use App\Models\Pays;
use App\Models\Transaction;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class WalletMobileApiController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    // ─────────────────────────────────────────────────────────────────────────
    //  GET /api/v1/wallet/balance
    //  Retourne le solde courant de l'utilisateur connecté
    // ─────────────────────────────────────────────────────────────────────────

    public function balance()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'solde'   => (float) $user->solde,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  GET /api/v1/wallet/transactions
    //  Historique paginé des recharges de l'utilisateur connecté
    // ─────────────────────────────────────────────────────────────────────────

    public function transactions(Request $request)
    {
        $user = Auth::user();

        $transactions = Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Format compatible avec le WalletService Flutter
        // (meta.current_page / meta.last_page pour la pagination)
        return response()->json([
            'success' => true,
            'data'    => $transactions->map(fn ($tx) => [
                'id'             => $tx->id,
                'reference'      => $tx->reference,
                'transaction_id' => $tx->transaction_id,
                'amount'         => (float) $tx->amount,
                'payment_method' => $tx->payment_method,
                'created_at'     => $tx->created_at->toIso8601String(),
            ]),
            'meta'    => [
                'total'        => $transactions->total(),
                'current_page' => $transactions->currentPage(),
                'last_page'    => $transactions->lastPage(),
                'per_page'     => $transactions->perPage(),
            ],
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  Helpers — identiques au WalletController web
    // ─────────────────────────────────────────────────────────────────────────

    private function convertToUSD(float $amountXOF): float
    {
        $usd  = Currency::where('code', 'USD')->first();
        $rate = $usd?->exchange_rate ?? 600;
        return round($amountXOF / $rate, 2);
    }

    private function convertUSDToXOF(float $amountUSD): float
    {
        $usd  = Currency::where('code', 'USD')->first();
        $rate = $usd?->exchange_rate ?? 600;
        return round($amountUSD * $rate, 2);
    }

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
            'locale'         => 'fr_FR',
            'validate_ssl'   => true,
        ]);
        $provider->getAccessToken();
        return $provider;
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  POST /api/v1/wallet/paypal/create-order
    //
    //  Body : { "amount_xof": 10000 }
    //
    //  Crée l'ordre PayPal et retourne l'approvalUrl + l'orderID.
    //  Le WebView Flutter intercepte le custom scheme sumbaawa://
    //  après approbation, exactement comme pour le checkout.
    // ─────────────────────────────────────────────────────────────────────────

    public function createPayPalOrder(Request $request)
    {
        $request->validate([
            'amount_xof' => 'required|numeric|min:100',
        ]);

        $amountXOF = (float) $request->amount_xof;
        $amountUSD = $this->convertToUSD($amountXOF);

        if ($amountUSD < 0.01) {
            return response()->json([
                'success' => false,
                'error'   => 'Montant trop faible (minimum ~60 XOF)',
            ], 400);
        }

        try {
            $provider = $this->getPayPalProvider();

            $order = $provider->createOrder([
                'intent'         => 'CAPTURE',
                'purchase_units' => [[
                    'amount'      => [
                        'currency_code' => 'USD',
                        'value'         => number_format($amountUSD, 2, '.', ''),
                    ],
                    'description' => 'Recharge de solde — ' . config('app.name'),
                    'custom_id'   => json_encode([
                        'amount_xof' => $amountXOF,
                        'user_id'    => Auth::id(),
                        'type'       => 'wallet_topup',
                    ]),
                ]],
                // Même custom scheme que le checkout mobile
                'application_context' => [
                    'cancel_url'          => 'sumbaawa://paypal/cancel',
                    'return_url'          => 'sumbaawa://paypal/success',
                    'brand_name'          => config('app.name'),
                    'user_action'         => 'PAY_NOW',
                    'shipping_preference' => 'NO_SHIPPING',
                ],
            ]);

            if (!isset($order['id'])) {
                Log::error('WalletMobile createPayPalOrder failed', $order);
                return response()->json([
                    'success' => false,
                    'error'   => 'Erreur lors de la création de l\'ordre PayPal',
                ], 500);
            }

            // Extraire l'approvalUrl depuis les liens retournés par PayPal
            $approvalUrl = collect($order['links'] ?? [])
                ->firstWhere('rel', 'approve')['href'] ?? null;

            Log::info('WalletMobile createPayPalOrder OK', [
                'order_id'   => $order['id'],
                'amount_xof' => $amountXOF,
                'amount_usd' => $amountUSD,
                'user_id'    => Auth::id(),
            ]);

            return response()->json([
                'success'      => true,
                'orderID'      => $order['id'],
                'approvalUrl'  => $approvalUrl,
                'amount_xof'   => $amountXOF,
                'amount_usd'   => $amountUSD,
            ]);

        } catch (\Exception $e) {
            Log::error('WalletMobile createPayPalOrder exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error'   => 'Erreur serveur : ' . $e->getMessage(),
            ], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  POST /api/v1/wallet/paypal/capture-order
    //
    //  Body : { "orderID": "PAYPAL_ORDER_ID" }
    //
    //  Capture le paiement, enregistre la transaction,
    //  crédite le solde de l'utilisateur.
    // ─────────────────────────────────────────────────────────────────────────

    public function capturePayPalOrder(Request $request)
    {
        $request->validate([
            'orderID' => 'required|string',
        ]);

        try {
            $provider = $this->getPayPalProvider();
            $result   = $provider->capturePaymentOrder($request->orderID);

            Log::info('WalletMobile capturePayPalOrder result', [
                'status'  => $result['status'] ?? 'unknown',
                'user_id' => Auth::id(),
            ]);

            if (($result['status'] ?? '') !== 'COMPLETED') {
                Log::error('WalletMobile capture not COMPLETED', $result);
                return response()->json([
                    'success' => false,
                    'error'   => 'Paiement non complété',
                ], 400);
            }

            // ── Montant réellement capturé par PayPal (source de vérité) ──
            $capturedUSD = (float) (
                $result['purchase_units'][0]['payments']['captures'][0]['amount']['value']
                ?? 0
            );
            $transactionId = $result['purchase_units'][0]['payments']['captures'][0]['id']
                ?? $result['id'];

            // Recalculer le XOF depuis le montant PayPal réel
            $amountXOF = $this->convertUSDToXOF($capturedUSD);

            // Vérification via custom_id si dispo (tolérance 1%)
            $customId = $result['purchase_units'][0]['custom_id'] ?? null;
            if ($customId) {
                $custom = json_decode($customId, true);
                $expectedXOF = (float) ($custom['amount_xof'] ?? 0);

                if ($expectedXOF > 0) {
                    $diff = abs($amountXOF - $expectedXOF) / $expectedXOF;
                    if ($diff > 0.01) {
                        Log::warning('WalletMobile XOF mismatch', [
                            'expected_xof' => $expectedXOF,
                            'captured_xof' => $amountXOF,
                            'captured_usd' => $capturedUSD,
                        ]);
                        // On utilise le montant recalculé depuis PayPal (plus sûr)
                    }
                }
            }

            $user = Auth::user();

            // ── 1. Enregistrer la transaction ──────────────────────────────
            Transaction::create([
                'reference'      => 'WAL-' . strtoupper(Str::random(10)),
                'transaction_id' => $transactionId,
                'amount'         => $amountXOF,
                'payment_method' => 'paypal',
                'user_id'        => $user->id,
                'admin_id'       => null,
            ]);

            // ── 2. Créditer le solde ───────────────────────────────────────
            $user->increment('solde', $amountXOF);

            Log::info('WalletMobile solde crédité', [
                'user_id'    => $user->id,
                'amount_xof' => $amountXOF,
                'amount_usd' => $capturedUSD,
                'new_solde'  => $user->solde + $amountXOF,
            ]);

            return response()->json([
                'success'    => true,
                'amount_xof' => $amountXOF,
                'amount_usd' => $capturedUSD,
                'message'    => 'Solde rechargé avec succès',
            ]);

        } catch (\Exception $e) {
            Log::error('WalletMobile capturePayPalOrder exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'error'   => 'Erreur lors du traitement : ' . $e->getMessage(),
            ], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  POST /api/wallet/checkout
    //  Miroir exact de WalletController::payWithWallet() web
    //  Flutter envoie : amount_xof, delivery_method, shipping (delivery_info),
    //                   items (panier)
    // ─────────────────────────────────────────────────────────────────────────

    public function checkout(Request $request)
    {
        $request->validate([
            'amount_xof'      => 'required|numeric|min:1',
            'delivery_method' => 'required|in:tinda_awa,cargo',
            'shipping'        => 'required|array',
            'items'           => 'required|array|min:1',
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Non authentifié'], 401);
        }

        try {
            DB::beginTransaction();

            $amountXOF      = (float) $request->amount_xof;
            $shipping       = $request->shipping;
            $deliveryMethod = $request->delivery_method;
            $cartItems      = $request->items;

            // ── Vérifier le solde ─────────────────────────────────────────
            // Identique à WalletController web : solde insuffisant → 400
            if ($user->solde < $amountXOF) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Solde insuffisant. Votre solde est de '
                                 . number_format($user->solde, 0, ',', ' ') . ' XOF.',
                ], 400);
            }

            // ── Construire adresse + type_id — miroir exact du web ────────
            $code   = 'CMD-' . strtoupper(Str::random(8));
            $typeId = $shipping['deliveryType'] ?? null;
            $address = null;

            if ($deliveryMethod === 'tinda_awa') {
                $pays    = Pays::find($shipping['country'] ?? null);
                $address = "Livraison Tinda Awa - " .
                           "Pays: " . ($pays ? $pays->name : 'N/A') . ", " .
                           "Ville: " . ($shipping['city'] ?? '') . ", " .
                           "Adresse: " . ($shipping['address'] ?? '') . ", " .
                           "Destinataire: " . ($shipping['recipientName'] ?? '') . ", " .
                           "Tél: " . ($shipping['recipientPhone'] ?? '');
            } else {
                $address = "Livraison Cargo - " .
                           "Ville: " . ($shipping['city'] ?? '') . ", " .
                           "Adresse: " . ($shipping['address'] ?? '') . ", " .
                           "Contact: " . ($shipping['contactName'] ?? '') . ", " .
                           "Tél: " . ($shipping['contactPhone'] ?? '');
            }

            // ── Identifiant de transaction portefeuille ────────────────────
            $walletTransactionId = 'WALLET-' . $user->id . '-' . now()->format('YmdHis');

            // ── Créer la commande ─────────────────────────────────────────
            $commande = Commande::create([
                'user_id'         => $user->id,
                'code'            => $code,
                'address'         => $address,
                'type_id'         => $typeId,
                'delivery_method' => $deliveryMethod,
                'delivery_info'   => json_encode($shipping), // identique au web
                'payment_method'  => 'wallet',
                'payment_status'  => 'paid',
                'status'          => 'pending',
                'payment_id'      => $walletTransactionId,
                'payment_email'   => $user->email,
                'total_amount'    => $amountXOF,
                'amount_usd'      => null,
                'currency'        => 'XOF',
            ]);

            // ── Ajouter les produits ──────────────────────────────────────
            foreach ($cartItems as $item) {
                $description = "Couleur: "        . ($item['color']          ?? 'N/A') . ", " .
                               "Niveau confort: " . ($item['niveau_confort'] ?? 'N/A') . ", " .
                               "Poids: "          . ($item['poids']          ?? 0)     . " kg";

                Commander::create([
                    'commande_id'         => $commande->id,
                    'produit_id'          => $item['product_id'],
                    'quantity'            => $item['quantity'],
                    'description_produit' => $description,
                    'unit_price'          => $item['price'],
                    'total_price'         => $item['price'] * $item['quantity'],
                ]);
            }

            // ── Défalquer le solde (atomique) ─────────────────────────────
            // decrement() est atomique en SQL : UPDATE users SET solde = solde - X WHERE id = Y
            $user->decrement('solde', $amountXOF);

            DB::commit();

            Log::info('WalletMobile checkout OK', [
                'commande_id'    => $commande->id,
                'code'           => $code,
                'amount_xof'     => $amountXOF,
                'new_solde'      => $user->fresh()->solde,
                'delivery_method'=> $deliveryMethod,
            ]);

            // ── Email — même logique que le web ───────────────────────────
            try {
                $deliveryData = array_merge($shipping, ['deliveryMethod' => $deliveryMethod]);
                Mail::to($user->email)
                    ->send(new DeliveryProcessingEmail($commande, $deliveryData));
            } catch (\Exception $e) {
                Log::error('WalletMobile checkout email error: ' . $e->getMessage());
            }

            return response()->json([
                'success'    => true,
                'order_id'   => $commande->id,
                'order_code' => $code,
                'amount_xof' => $amountXOF,
                'new_solde'  => $user->fresh()->solde,
                'message'    => 'Commande enregistrée avec succès',
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('WalletMobile checkout exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  POST /api/shipping/wallet/capture
    //  Miroir exact de ShippingPaymentController::payWithWallet() web
    //  Flutter envoie : commande_id, amount_xof
    // ─────────────────────────────────────────────────────────────────────────

    public function captureShippingFee(Request $request)
    {
        $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'amount_xof'  => 'required|numeric|min:1',
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Non authentifié'], 401);
        }

        try {
            // ── Récupérer et valider la commande ──────────────────────────
            // Identique à getValidCommande() dans ShippingPaymentController web
            $commande = Commande::where('id', $request->commande_id)
                ->where('user_id', $user->id)
                ->where('status', 'ready_pickup')
                ->whereNotNull('shipping_fee')
                ->where('shipping_status', 'fee_pending')
                ->firstOrFail();

            // ── Vérification cohérence montant vs frais enregistrés (1% tolérance) ──
            $amountXOF = (float) $request->amount_xof;
            $tolerance = $commande->shipping_fee * 0.01;
            if (abs($amountXOF - $commande->shipping_fee) > $tolerance) {
                Log::warning('WalletMobile shipping: écart montant', [
                    'frais_enregistres' => $commande->shipping_fee,
                    'montant_fourni'    => $amountXOF,
                ]);
                // Source de vérité = frais enregistrés côté serveur
                $amountXOF = $commande->shipping_fee;
            }

            // ── Vérifier le solde ─────────────────────────────────────────
            if ($user->solde < $amountXOF) {
                return response()->json([
                    'success' => false,
                    'message' => 'Solde insuffisant. Votre solde est de '
                                 . number_format($user->solde, 0, ',', ' ') . ' XOF.',
                ], 400);
            }

            // ── Défalquer le solde (atomique) ─────────────────────────────
            $user->decrement('solde', $amountXOF);

            // ── Identifiant de transaction portefeuille ────────────────────
            $walletTransactionId = 'WALLET-SHIP-' . $user->id . '-' . now()->format('YmdHis');

            // ── Marquer la commande comme payée ───────────────────────────
            // Identique à markShippingPaid() dans ShippingPaymentController web
            $commande->shipping_status         = 'fee_paid';
            $commande->shipping_payment_id     = $walletTransactionId;
            $commande->shipping_payment_method = 'wallet';
            $commande->shipping_payment_date   = now();
            $commande->save();

            // ── Notification ─────────────────────────────────────────────
            try {
                $this->notificationService->notifyShippingPaymentSuccess($commande);
            } catch (\Exception $e) {
                Log::error('WalletMobile shipping notification error: ' . $e->getMessage());
            }

            Log::info('WalletMobile shipping fee paid', [
                'commande_id'    => $commande->id,
                'amount_xof'     => $amountXOF,
                'user_id'        => $user->id,
                'new_solde'      => $user->fresh()->solde,
            ]);

            return response()->json([
                'success'     => true,
                'message'     => 'Paiement des frais de livraison réussi',
                'commande_id' => $commande->id,
                'amount_xof'  => $amountXOF,
                'new_solde'   => $user->fresh()->solde,
            ]);

        } catch (\Exception $e) {
            Log::error('WalletMobile shipping exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur: ' . $e->getMessage(),
            ], 500);
        }
    }
}
