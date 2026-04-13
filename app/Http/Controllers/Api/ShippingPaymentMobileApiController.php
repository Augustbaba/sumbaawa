<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Services\NotificationService;

class ShippingPaymentMobileApiController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    private function convertToUSD(float $amountXOF): float
    {
        $usdCurrency = Currency::where('code', 'USD')->first();
        $usdRate     = $usdCurrency?->exchange_rate ?? 600;
        return round($amountXOF / $usdRate, 2);
    }

    private function makeProvider(): PayPalClient
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
            'locale'         => 'fr_FR',
            'validate_ssl'   => true,
        ]);
        $provider->getAccessToken();
        return $provider;
    }

    /**
     * POST /api/shipping/paypal/create-order
     * Miroir de ShippingPaymentController::createPayPalOrder()
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'amount_xof'  => 'required|numeric|min:1',
        ]);

        $commande = Commande::where('id', $request->commande_id)
            ->where('user_id', Auth::id())
            ->where('status', 'ready_pickup')
            ->whereNotNull('shipping_fee')
            ->where('shipping_status', 'fee_pending')
            ->firstOrFail();

        try {
            // Vérification cohérence montant (2% tolérance, identique au web)
            $amountXOF = (float) $request->amount_xof;
            $tolerance = $commande->shipping_fee * 0.02;
            if (abs($amountXOF - $commande->shipping_fee) > $tolerance) {
                Log::warning('ShippingMobile: écart montant', [
                    'fee_commande' => $commande->shipping_fee,
                    'amount_recu'  => $amountXOF,
                ]);
                $amountXOF = $commande->shipping_fee;
            }

            $amountUSD = $this->convertToUSD($amountXOF);

            if ($amountUSD < 0.01) {
                return response()->json([
                    'success' => false,
                    'message' => 'Montant trop faible pour PayPal',
                ], 400);
            }

            $provider = $this->makeProvider();

            // Custom scheme intercepté par le WebView Flutter
            $returnUrl = 'sumbaawa://paypal/success';
            $cancelUrl = 'sumbaawa://paypal/cancel';

            $order = $provider->createOrder([
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'amount' => [
                        'currency_code' => 'USD',
                        'value'         => number_format($amountUSD, 2, '.', ''),
                    ],
                    'description' => 'Frais de livraison - Commande #' . $commande->code,
                    'custom_id'   => json_encode([
                        'commande_id' => $commande->id,
                        'amount_xof'  => $amountXOF,
                        'type'        => 'shipping_fee',
                    ]),
                ]],
                'application_context' => [
                    'cancel_url'          => $cancelUrl,
                    'return_url'          => $returnUrl,
                    'brand_name'          => config('app.name'),
                    'user_action'         => 'PAY_NOW',
                    'shipping_preference' => 'NO_SHIPPING',
                ],
            ]);

            if (!isset($order['id'])) {
                Log::error('ShippingMobile createOrder failed', $order);
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur création commande PayPal',
                ], 500);
            }

            $approvalUrl = collect($order['links'])
                ->firstWhere('rel', 'approve')['href'] ?? null;

            // Stocker en session pour la capture
            session([
                'mobile_shipping_paypal_id'  => $order['id'],
                'mobile_shipping_commande_id' => $commande->id,
                'mobile_shipping_xof'        => $amountXOF,
                'mobile_shipping_usd'        => $amountUSD,
            ]);

            return response()->json([
                'success'      => true,
                'approval_url' => $approvalUrl,
                'paypal_id'    => $order['id'],
            ]);

        } catch (\Exception $e) {
            Log::error('ShippingMobile createOrder exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/shipping/paypal/capture-order
     * Miroir de ShippingPaymentController::capturePayPalOrder()
     */
    public function captureOrder(Request $request)
    {
        $request->validate([
            'paypal_order_id' => 'required|string',
            'commande_id'     => 'required|exists:commandes,id',
            'amount_xof'      => 'required|numeric|min:1',
        ]);

        $commande = Commande::where('id', $request->commande_id)
            ->where('user_id', Auth::id())
            ->where('status', 'ready_pickup')
            ->whereNotNull('shipping_fee')
            ->where('shipping_status', 'fee_pending')
            ->firstOrFail();

        try {
            $provider = $this->makeProvider();
            $result   = $provider->capturePaymentOrder($request->paypal_order_id);

            if (($result['status'] ?? '') !== 'COMPLETED') {
                return response()->json([
                    'success' => false,
                    'message' => 'Capture PayPal échouée',
                ], 400);
            }

            $paypalAmount = (float) ($result['purchase_units'][0]['payments']['captures'][0]['amount']['value'] ?? 0);

            // Mise à jour commande — identique à ShippingPaymentController web
            $commande->shipping_status         = 'fee_paid';
            $commande->shipping_payment_id     = $result['id'];
            $commande->shipping_payment_method = 'paypal';
            $commande->shipping_payment_date   = now();
            $commande->save();

            // Notification (même logique que le web)
            try {
                $this->notificationService->notifyShippingPaymentSuccess($commande);
            } catch (\Exception $e) {
                Log::error('ShippingMobile notification error: ' . $e->getMessage());
            }

            // Nettoyage session
            session()->forget([
                'mobile_shipping_paypal_id',
                'mobile_shipping_commande_id',
                'mobile_shipping_xof',
                'mobile_shipping_usd',
            ]);

            Log::info('ShippingMobile capture OK', [
                'commande_id' => $commande->id,
                'paypal_id'   => $result['id'],
                'amount_usd'  => $paypalAmount,
            ]);

            return response()->json([
                'success'    => true,
                'message'    => 'Frais de livraison payés avec succès',
                'commande_id'=> $commande->id,
            ]);

        } catch (\Exception $e) {
            Log::error('ShippingMobile captureOrder exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du traitement: ' . $e->getMessage(),
            ], 500);
        }


    }

    /**
     * POST /api/shipping/elongopay/capture
     * Miroir exact de ShippingPaymentController::captureElongoPay() web
     * Appelé après réception de ELONGOPAY_SUCCESS dans ElongoPayWebViewScreen
     */
    public function captureElongoPay(Request $request)
    {
        try {
            $request->validate([
                'transaction_id' => 'required|string',
                'amount_xof'     => 'required|numeric|min:1',
                'commande_id'    => 'required|exists:commandes,id',
            ]);

            $commande = Commande::where('id', $request->commande_id)
                ->where('user_id', Auth::id())
                ->where('status', 'ready_pickup')
                ->whereNotNull('shipping_fee')
                ->where('shipping_status', 'fee_pending')
                ->firstOrFail();

            // Vérification de cohérence vs frais enregistrés (2% tolérance) — identique au web
            $tolerance = $commande->shipping_fee * 0.02;
            if (abs($request->amount_xof - $commande->shipping_fee) > $tolerance) {
                Log::warning('ShippingMobile ElongoPay: écart montant', [
                    'frais_commande' => $commande->shipping_fee,
                    'montant_recu'   => $request->amount_xof,
                ]);
            }

            $commande->shipping_status         = 'fee_paid';
            $commande->shipping_payment_id     = $request->transaction_id;
            $commande->shipping_payment_method = 'elongopay';
            $commande->shipping_payment_date   = now();
            $commande->save();

            try {
                $this->notificationService->notifyShippingPaymentSuccess($commande);
            } catch (\Exception $e) {
                Log::error('ShippingMobile ElongoPay notification error: ' . $e->getMessage());
            }

            Log::info('ShippingMobile ElongoPay capture OK', [
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
            Log::error('ShippingMobile ElongoPay exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'error'   => 'Erreur lors du traitement: ' . $e->getMessage(),
            ], 500);
        }
    }
}
