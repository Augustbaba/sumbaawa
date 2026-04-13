<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Transaction;
use App\Models\Currency;
use App\Helpers\FrontHelper;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class WalletController extends Controller
{
    public function recharge()
    {
        return view('back.pages.wallet.recharge');
    }

    public function index()
    {
        $user = Auth::user();

        $transactions = Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('back.pages.wallet.index', compact('user', 'transactions'));
    }

    // ─── Convertir XOF → USD ─────────────────────────────────────────────────
    private function convertToUSD(float $amountXOF): float
    {
        $usd = Currency::where('code', 'USD')->first();
        $rate = $usd ? $usd->exchange_rate : 600;
        return round($amountXOF / $rate, 2);
    }

    private function convertUSDToXOF(float $amountUSD): float
    {
        $usd = Currency::where('code', 'USD')->first();
        $rate = $usd ? $usd->exchange_rate : 600;
        return round($amountUSD * $rate, 2);
    }

    // ─── Initialiser le provider PayPal ──────────────────────────────────────
    private function getPayPalProvider(): PayPalClient
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials([
            'mode' => config('services.paypal.mode', 'sandbox'),
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

    // ─── Créer la commande PayPal ─────────────────────────────────────────────
    public function createPayPalOrder(Request $request)
    {
        try {
            $request->validate([
                'amount_xof' => 'required|numeric|min:100', // minimum 100 XOF
            ]);

            $amountXOF = (float) $request->amount_xof;
            $amountUSD = $this->convertToUSD($amountXOF);

            if ($amountUSD < 0.01) {
                return response()->json(['error' => 'Montant trop faible (minimum ~6 XOF)'], 400);
            }

            $provider = $this->getPayPalProvider();

            $order = $provider->createOrder([
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'amount' => [
                        'currency_code' => 'USD',
                        'value'         => number_format($amountUSD, 2, '.', ''),
                    ],
                    'description' => 'Recharge de solde - ' . config('app.name'),
                    'custom_id'   => json_encode([
                        'amount_xof' => $amountXOF,
                        'user_id'    => Auth::id(),
                        'type'       => 'wallet_topup',
                    ]),
                ]],
                'application_context' => [
                    'cancel_url' => route('wallet.index'),
                    'return_url' => route('wallet.index'),
                ],
            ]);

            if (!isset($order['id'])) {
                Log::error('Erreur création ordre PayPal wallet', $order);
                return response()->json(['error' => 'Erreur PayPal'], 500);
            }

            // Stocker en session pour vérification lors de la capture
            session([
                'wallet_paypal_order_id' => $order['id'],
                'wallet_paypal_xof'      => $amountXOF,
                'wallet_paypal_usd'      => $amountUSD,
            ]);

            Log::info('Ordre PayPal wallet créé', [
                'order_id'   => $order['id'],
                'amount_xof' => $amountXOF,
                'amount_usd' => $amountUSD,
            ]);

            return response()->json(['success' => true, 'orderID' => $order['id']]);

        } catch (\Exception $e) {
            Log::error('createPayPalOrder wallet: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ─── Capturer le paiement et créditer le solde ────────────────────────────
    public function capturePayPalOrder(Request $request)
    {
        try {
            $request->validate(['orderID' => 'required|string']);

            $provider = $this->getPayPalProvider();
            $result   = $provider->capturePaymentOrder($request->orderID);

            Log::info('Capture PayPal wallet', $result);

            if (($result['status'] ?? '') !== 'COMPLETED') {
                return response()->json(['success' => false, 'error' => 'Paiement non complété'], 400);
            }

            // Montant réellement capturé par PayPal (source de vérité)
            $capturedUSD = (float) ($result['purchase_units'][0]['payments']['captures'][0]['amount']['value'] ?? 0);
            $transactionId = $result['purchase_units'][0]['payments']['captures'][0]['id'] ?? $result['id'];

            // Recalculer le XOF depuis le montant PayPal réel (sécurité)
            $amountXOF = $this->convertUSDToXOF($capturedUSD);

            // Double-check avec la session (tolérance 1%)
            $sessionXOF = session('wallet_paypal_xof', 0);
            if ($sessionXOF > 0 && abs($amountXOF - $sessionXOF) / $sessionXOF > 0.01) {
                Log::warning('Écart wallet XOF', [
                    'session_xof'  => $sessionXOF,
                    'captured_xof' => $amountXOF,
                ]);
            }

            $user = Auth::user();

            // 1. Enregistrer la transaction
            Transaction::create([
                'reference'      => 'WAL-' . strtoupper(Str::random(10)),
                'transaction_id' => $transactionId,
                'amount'         => $amountXOF,
                'payment_method' => 'paypal',
                'user_id'        => $user->id,
                'admin_id'       => null,
            ]);

            // 2. Créditer le solde de l'utilisateur
            $user->increment('solde', $amountXOF);

            // Nettoyer la session
            session()->forget(['wallet_paypal_order_id', 'wallet_paypal_xof', 'wallet_paypal_usd']);

            Log::info('Solde rechargé', [
                'user_id'    => $user->id,
                'amount_xof' => $amountXOF,
                'new_solde'  => $user->fresh()->solde,
            ]);

            return response()->json([
                'success'    => true,
                'amount_xof' => $amountXOF,
                'amount_usd' => $capturedUSD,
                'message'    => 'Solde rechargé avec succès',
            ]);

        } catch (\Exception $e) {
            Log::error('capturePayPalOrder wallet: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
