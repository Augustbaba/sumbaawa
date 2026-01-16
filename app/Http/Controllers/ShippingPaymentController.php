<?php

namespace App\Http\Controllers;

use App\Mail\ShippingPaymentConfirmationEmail;
use App\Models\Commande;
use App\Models\Currency;
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

    private function convertToUSD($amount, $fromCurrency = 'XOF')
    {
        // Si déjà en USD, retourner le montant
        if ($fromCurrency === 'USD') {
            return round($amount, 2);
        }

        // Récupérer le taux de change USD depuis la BD
        $usdCurrency = Currency::where('code', 'USD')->first();

        if (!$usdCurrency) {
            // Fallback sur un taux par défaut si non trouvé
            Log::warning('Devise USD non trouvée dans la BD, utilisation du taux par défaut');
            return round($amount / 600, 2); // Taux par défaut XOF -> USD
        }

        $usdRate = $usdCurrency->exchange_rate;

        if ($fromCurrency === 'XOF') {
            // Conversion directe XOF vers USD
            return round($amount / $usdRate, 2);
        } else {
            // Pour les autres devises, passer d'abord par XOF
            $fromCurrencyObj = Currency::where('code', $fromCurrency)->first();

            if (!$fromCurrencyObj) {
                Log::warning("Devise {$fromCurrency} non trouvée, conversion directe");
                return round($amount / $usdRate, 2);
            }

            $fromRate = $fromCurrencyObj->exchange_rate;

            // Étape 1: Convertir vers XOF
            $amountInXOF = $amount * $fromRate;

            // Étape 2: Convertir XOF vers USD
            return round($amountInXOF / $usdRate, 2);
        }
    }

    /**
     * Convertir USD vers XOF (pour vérification)
     */
    private function convertUSDToXOF($amountUSD)
    {
        $usdCurrency = Currency::where('code', 'USD')->first();

        if (!$usdCurrency) {
            return round($amountUSD * 600, 2); // Taux par défaut
        }

        return round($amountUSD * $usdCurrency->exchange_rate, 2);
    }

    /**
     * Créer une commande PayPal pour les frais de livraison
     */
    public function createPayPalOrder(Request $request)
    {
        try {
            Log::info('Création commande PayPal frais de livraison', $request->all());

            $request->validate([
                'commande_id' => 'required|exists:commandes,id',
                'amount' => 'required|numeric|min:0.01', // Montant en USD
                'currency' => 'nullable|string', // Code de la devise utilisée
                'amountInXOF' => 'nullable|numeric' // Montant en XOF
            ]);

            // Vérifier que la commande appartient à l'utilisateur
            $commande = Commande::where('id', $request->commande_id)
                ->where('user_id', Auth::id())
                ->where('status', 'ready_pickup')
                ->whereNotNull('shipping_fee')
                ->where('shipping_status', 'fee_pending')
                ->firstOrFail();

            // Récupérer ou calculer le montant en USD
            $amountUSD = $request->amount;
            $currency = $request->currency ?? 'XOF';
            $amountInXOF = $request->amountInXOF ?? $commande->shipping_fee;

            // Vérifier la cohérence du montant avec les frais de livraison enregistrés
            $tolerance = $commande->shipping_fee * 0.01; // 1% de tolérance
            if (abs($amountInXOF - $commande->shipping_fee) > $tolerance) {
                Log::warning('Écart entre frais de livraison enregistrés et montant fourni', [
                    'frais_enregistres' => $commande->shipping_fee,
                    'montant_fourni' => $amountInXOF
                ]);

                // Utiliser les frais enregistrés pour plus de sécurité
                $amountInXOF = $commande->shipping_fee;
            }

            // Recalculer le montant USD depuis XOF pour vérification
            $calculatedUSD = $this->convertToUSD($amountInXOF, 'XOF');

            // Tolérance de 1% pour les différences d'arrondi
            $usdTolerance = $calculatedUSD * 0.01;

            if (abs($calculatedUSD - $amountUSD) > $usdTolerance) {
                Log::warning('Écart de conversion détecté pour frais de livraison', [
                    'montant_fourni_usd' => $amountUSD,
                    'montant_calcule_usd' => $calculatedUSD,
                    'montant_xof' => $amountInXOF
                ]);

                // Utiliser le montant recalculé
                $amountUSD = $calculatedUSD;
            }

            // Arrondir à 2 décimales
            $amountUSD = round($amountUSD, 2);

            // Vérifier le montant minimum PayPal
            if ($amountUSD < 0.01) {
                return response()->json([
                    'error' => 'Le montant est trop faible pour être traité par PayPal (minimum $0.01)'
                ], 400);
            }

            Log::info('Montants frais de livraison', [
                'commande_id' => $commande->id,
                'frais_xof' => $amountInXOF,
                'montant_usd' => $amountUSD,
                'devise_client' => $currency
            ]);

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

            $order = $provider->createOrder([
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => number_format($amountUSD, 2, '.', '')
                        ],
                        "description" => "Frais de livraison - Commande #" . $commande->code,
                        "custom_id" => json_encode([
                            'commande_id' => $commande->id,
                            'amount_xof' => $amountInXOF,
                            'currency' => $currency,
                            'type' => 'shipping_fee'
                        ])
                    ]
                ],
                "application_context" => [
                    "cancel_url" => route('shipping.payment.cancel', $commande->id),
                    "return_url" => route('shipping.payment.success')
                ]
            ]);

            if (isset($order['id']) && $order['id'] != null) {
                // Stocker les informations dans la session
                session([
                    'shipping_payment_order_id' => $order['id'],
                    'shipping_commande_id' => $commande->id,
                    'shipping_amount_usd' => $amountUSD,
                    'shipping_amount_xof' => $amountInXOF,
                    'shipping_currency' => $currency
                ]);

                Log::info('Commande PayPal frais de livraison créée', [
                    'order_id' => $order['id'],
                    'commande_id' => $commande->id,
                    'amount_usd' => $amountUSD,
                    'amount_xof' => $amountInXOF
                ]);

                return response()->json([
                    'success' => true,
                    'orderID' => $order['id']
                ]);
            }

            Log::error('Erreur création commande PayPal frais de livraison', $order);
            return response()->json([
                'error' => 'Erreur lors de la création du paiement'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Exception createShippingPayPalOrder: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Erreur serveur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Capturer le paiement PayPal des frais de livraison
     */
    public function capturePayPalOrder(Request $request)
    {
        try {
            Log::info('Capture paiement PayPal frais de livraison', $request->all());

            $request->validate([
                'orderID' => 'required',
                'currency' => 'nullable|string',
                'amountInXOF' => 'nullable|numeric'
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

            Log::info('Résultat capture PayPal frais de livraison', $result);

            if (isset($result['status']) && $result['status'] == 'COMPLETED') {
                // Récupérer les montants de la session
                $amountInXOF = $request->amountInXOF ?? session('shipping_amount_xof', $commande->shipping_fee);
                $amountUSD = session('shipping_amount_usd');
                $currency = $request->currency ?? session('shipping_currency', 'XOF');

                // Vérification de sécurité : comparer avec le montant PayPal
                $paypalAmountUSD = floatval($result['purchase_units'][0]['payments']['captures'][0]['amount']['value'] ?? 0);

                if ($paypalAmountUSD > 0 && abs($paypalAmountUSD - $amountUSD) > 0.02) {
                    Log::warning('Écart entre montant session et PayPal pour frais de livraison', [
                        'session_usd' => $amountUSD,
                        'paypal_usd' => $paypalAmountUSD
                    ]);

                    // Recalculer le montant XOF depuis PayPal
                    $amountInXOF = $this->convertUSDToXOF($paypalAmountUSD);
                    $amountUSD = $paypalAmountUSD;
                }

                // Vérifier la cohérence avec les frais de livraison
                $tolerance = $commande->shipping_fee * 0.02; // 2% de tolérance
                if (abs($amountInXOF - $commande->shipping_fee) > $tolerance) {
                    Log::warning('Écart entre frais de livraison et montant payé', [
                        'frais_commande' => $commande->shipping_fee,
                        'montant_paye' => $amountInXOF
                    ]);
                }

                Log::info('Montants finaux frais de livraison', [
                    'commande_id' => $commande->id,
                    'amount_xof' => $amountInXOF,
                    'amount_usd' => $amountUSD,
                    'currency' => $currency,
                    'paypal_amount' => $paypalAmountUSD
                ]);

                // Mettre à jour la commande avec les informations de devise
                $commande->shipping_status = 'fee_paid';
                $commande->shipping_payment_id = $result['id'];
                $commande->shipping_payment_date = now();
                // $commande->shipping_amount_usd = $amountUSD;
                // $commande->shipping_currency = $currency;
                $commande->save();

                // Envoyer email de confirmation
                try {
                    $this->sendShippingPaymentConfirmation($commande);
                } catch (\Exception $e) {
                    Log::error('Erreur envoi email confirmation frais de livraison: ' . $e->getMessage());
                }

                // Nettoyer la session
                session()->forget([
                    'shipping_payment_order_id',
                    'shipping_commande_id',
                    'shipping_amount_usd',
                    'shipping_amount_xof',
                    'shipping_currency'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Paiement des frais de livraison réussi',
                    'commande_id' => $commande->id,
                    'amount_xof' => $amountInXOF,
                    'amount_usd' => $amountUSD,
                    'currency' => $currency
                ]);
            } else {
                Log::error('Capture PayPal frais de livraison échouée', $result);
                return response()->json([
                    'success' => false,
                    'error' => 'Le paiement n\'a pas pu être capturé'
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Exception captureShippingPayPalOrder: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
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
        session()->forget([
            'shipping_payment_order_id',
            'shipping_commande_id',
            'shipping_amount_usd',
            'shipping_amount_xof',
            'shipping_currency'
        ]);

        return view('front.pages.shipping-payment-success', compact('commande'));
    }

    /**
     * Page d'annulation du paiement
     */
    public function cancel($commandeId)
    {
        // Nettoyer la session
        session()->forget([
            'shipping_payment_order_id',
            'shipping_commande_id',
            'shipping_amount_usd',
            'shipping_amount_xof',
            'shipping_currency'
        ]);

        return redirect()->route('shipping.payment.show', $commandeId)
            ->with('error', 'Paiement annulé');
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
