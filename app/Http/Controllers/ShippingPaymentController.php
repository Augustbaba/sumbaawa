<?php

namespace App\Http\Controllers;

use App\Mail\ShippingPaymentConfirmationEmail;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class ShippingPaymentController extends Controller
{
    /**
     * Afficher la page de paiement des frais de livraison
     */
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

    /**
     * Créer une commande PayPal pour les frais de livraison
     */
    public function createPayPalOrder(Request $request)
    {
        try {
            $request->validate([
                'commande_id' => 'required|exists:commandes,id',
                'amount' => 'required|numeric|min:0.01'
            ]);

            // Vérifier que la commande appartient à l'utilisateur
            $commande = Commande::where('id', $request->commande_id)
                ->where('user_id', Auth::id())
                ->where('status', 'ready_pickup')
                ->whereNotNull('shipping_fee')
                ->where('shipping_status', 'fee_pending')
                ->firstOrFail();

            $provider = new PayPalClient;

            $config = [
                'mode' => config('services.paypal.mode', 'sandbox'),
                'sandbox' => [
                    'client_id' => config('services.paypal.sandbox.client_id'),
                    'client_secret' => config('services.paypal.sandbox.client_secret'),
                    'app_id' => 'APP-80W284485P519543T',
                ],
                'live' => [
                    'client_id' => config('services.paypal.live.client_id'),
                    'client_secret' => config('services.paypal.live.client_secret'),
                    'app_id' => config('services.paypal.live.app_id'),
                ],
                'payment_action' => 'Sale',
                'currency' => 'USD',
                'notify_url' => '',
                'locale' => 'en_US',
                'validate_ssl' => true,
            ];

            $provider->setApiCredentials($config);
            $token = $provider->getAccessToken();

            $amountUSD = round($request->amount, 2);

            $order = $provider->createOrder([
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => $amountUSD
                        ],
                        "description" => "Frais de livraison - Commande #" . $commande->code,
                        "custom_id" => $commande->id
                    ]
                ],
                "application_context" => [
                    "cancel_url" => route('shipping.payment.cancel', $commande->id),
                    "return_url" => route('shipping.payment.success')
                ]
            ]);

            if (isset($order['id']) && $order['id'] != null) {
                // Stocker temporairement l'ID de commande pour la livraison
                session(['shipping_payment_order_id' => $order['id']]);
                session(['shipping_commande_id' => $commande->id]);

                return response()->json(['orderID' => $order['id']]);
            }

            Log::error('Erreur création commande PayPal frais de livraison', $order);
            return response()->json(['error' => 'Erreur lors de la création du paiement'], 500);

        } catch (\Exception $e) {
            Log::error('Exception createShippingPayPalOrder: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Capturer le paiement PayPal des frais de livraison
     */
    public function capturePayPalOrder(Request $request)
    {
        try {
            $request->validate([
                'orderID' => 'required'
            ]);

            // Récupérer les données de session
            $commandeId = session('shipping_commande_id');
            if (!$commandeId) {
                return response()->json([
                    'success' => false,
                    'error' => 'Session expirée'
                ], 400);
            }

            $commande = Commande::where('id', $commandeId)
                ->where('user_id', Auth::id())
                ->where('status', 'ready_pickup')
                ->whereNotNull('shipping_fee')
                ->where('shipping_status', 'fee_pending')
                ->firstOrFail();

            $provider = new PayPalClient;

            $config = [
                'mode' => config('services.paypal.mode', 'sandbox'),
                'sandbox' => [
                    'client_id' => config('services.paypal.sandbox.client_id'),
                    'client_secret' => config('services.paypal.sandbox.client_secret'),
                    'app_id' => 'APP-80W284485P519543T',
                ],
                'live' => [
                    'client_id' => config('services.paypal.live.client_id'),
                    'client_secret' => config('services.paypal.live.client_secret'),
                    'app_id' => config('services.paypal.live.app_id'),
                ],
                'payment_action' => 'Sale',
                'currency' => 'USD',
                'notify_url' => '',
                'locale' => 'en_US',
                'validate_ssl' => true,
            ];

            $provider->setApiCredentials($config);
            $provider->getAccessToken();

            $result = $provider->capturePaymentOrder($request->orderID);

            if (isset($result['status']) && $result['status'] == 'COMPLETED') {
                // Mettre à jour la commande
                $commande->shipping_status = 'fee_paid';
                $commande->shipping_payment_id = $result['id'];
                $commande->shipping_payment_date = now();
                $commande->save();

                // Envoyer email de confirmation
                $this->sendShippingPaymentConfirmation($commande);

                // Nettoyer la session
                // session()->forget('shipping_payment_order_id');
                // session()->forget('shipping_commande_id');

                return response()->json([
                    'success' => true,
                    'message' => 'Paiement des frais de livraison réussi',
                    'commande_id' => $commande->id
                ]);
            } else {
                Log::error('Capture PayPal frais de livraison échouée', $result);
                return response()->json([
                    'success' => false,
                    'error' => 'Le paiement n\'a pas pu être capturé'
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Exception captureShippingPayPalOrder: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors du traitement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Page de succès du paiement
     */
    public function success()
    {
        $commandeId = session('shipping_commande_id');

        if (!$commandeId) {
            return redirect()->route('dashboard')->with('error', 'Session expirée');
        }

        $commande = Commande::where('id', $commandeId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Nettoyer la session
        session()->forget('shipping_payment_order_id');
        session()->forget('shipping_commande_id');

        return view('front.pages.shipping-payment-success', compact('commande'));
    }

    /**
     * Page d'annulation du paiement
     */
    public function cancel($commandeId)
    {
        // Nettoyer la session
        session()->forget('shipping_payment_order_id');
        session()->forget('shipping_commande_id');

        return redirect()->route('shipping.payment.show', $commandeId)
            ->with('error', 'Paiement annulé. Vous pouvez réessayer.');
    }

    /**
     * Envoyer email de confirmation de paiement des frais de livraison
     */
    private function sendShippingPaymentConfirmation($commande)
    {
        try {
            $user = $commande->user;

            Mail::to($user->email)->send(new ShippingPaymentConfirmationEmail($commande));

            Log::info('Email confirmation frais de livraison envoyé', [
                'commande_id' => $commande->id,
                'user_id' => $user->id,
                'amount' => $commande->shipping_fee
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur envoi email confirmation frais de livraison: ' . $e->getMessage());
        }
    }
}
