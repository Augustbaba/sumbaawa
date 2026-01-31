<?php

namespace App\Http\Controllers;

use App\Mail\NihaoTravelConfirmation;
use App\Models\Currency;
use App\Models\Travel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class NihaoTravelController extends Controller
{
    public function index()
    {
        // Montant par défaut (facilement modifiable)
        $nihaoAmountXOF = 500000;

        return view('front.pages.nihao-travel', compact('nihaoAmountXOF'));
    }

    /**
     * Convertir XOF vers USD
     */
    private function convertToUSD($amountXOF)
    {
        // Récupérer le taux de change USD depuis la BD
        $usdCurrency = Currency::where('code', 'USD')->first();

        if (!$usdCurrency) {
            // Fallback sur un taux par défaut si non trouvé
            // Log::warning('Devise USD non trouvée dans la BD, utilisation du taux par défaut');
            return round($amountXOF / 600, 2); // Taux par défaut XOF -> USD
        }

        // Formule : montant_xof / taux_usd
        return round($amountXOF / $usdCurrency->exchange_rate, 2);
    }

    /**
     * Créer une commande PayPal pour Nihao Travel
     */
    public function createPayPalOrder(Request $request)
    {
        try {
            // Log::info('Création commande PayPal Nihao Travel', $request->all());

            $request->validate([
                'amount' => 'required|numeric|min:0.01', // Montant en USD
                'amountXOF' => 'required|numeric|min:1000', // Montant en XOF
                'formData' => 'required|array',
                'formData.first_name' => 'required|string|max:100',
                'formData.last_name' => 'required|string|max:100',
                'formData.email' => 'required|email|max:100',
                'formData.phone' => 'required|string|max:20',
                'formData.canton_edition' => 'required|string'
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

            $amountUSD = $request->amount;
            $amountXOF = $request->amountXOF;

            // Vérifier la conversion
            $calculatedUSD = $this->convertToUSD($amountXOF);
            $tolerance = $calculatedUSD * 0.005; // Tolérance de 0.5%

            if (abs($calculatedUSD - $amountUSD) > $tolerance) {
                // Log::warning('Écart de conversion détecté pour Nihao Travel', [
                //     'montant_fourni_usd' => $amountUSD,
                //     'montant_calcule_usd' => $calculatedUSD,
                //     'montant_xof' => $amountXOF
                // ]);

                $amountUSD = $calculatedUSD;
            }

            $amountUSD = round($amountUSD, 2);

            // Vérifier le montant minimum
            if ($amountUSD < 0.01) {
                return response()->json([
                    'error' => 'Le montant est trop faible pour être traité par PayPal (minimum $0.01)'
                ], 400);
            }

            // Log::info('Montants Nihao Travel', [
            //     'montant_xof' => $amountXOF,
            //     'montant_usd' => $amountUSD
            // ]);

            $order = $provider->createOrder([
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => number_format($amountUSD, 2, '.', '')
                        ],
                        "description" => "Forfait Nihao Travel - Foire de Canton",
                        "custom_id" => json_encode([
                            'amount_xof' => $amountXOF,
                            'service' => 'nihao_travel',
                        ])
                    ]
                ],
                "application_context" => [
                    "cancel_url" => route('nihao.travel.cancel'),
                    "return_url" => route('nihao.travel.success')
                ]
            ]);

            if (isset($order['id']) && $order['id'] != null) {
                // Stocker les informations dans la session
                session([
                    'nihao_travel_order_id' => $order['id'],
                    'nihao_travel_amount_usd' => $amountUSD,
                    'nihao_travel_amount_xof' => $amountXOF,
                    'nihao_travel_form_data' => $request->formData
                ]);

                // Log::info('Commande PayPal Nihao Travel créée avec succès', [
                //     'order_id' => $order['id'],
                //     'amount_usd' => $amountUSD,
                //     'amount_xof' => $amountXOF
                // ]);

                return response()->json([
                    'success' => true,
                    'orderID' => $order['id']
                ]);
            }

            // Log::error('Erreur création commande PayPal Nihao Travel', $order);
            return response()->json([
                'error' => 'Erreur lors de la création de la commande'
            ], 500);

        } catch (\Exception $e) {
            // Log::error('Exception createPayPalOrder Nihao Travel: ' . $e->getMessage(), [
            //     'trace' => $e->getTraceAsString()
            // ]);
            return response()->json([
                'error' => 'Erreur serveur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Capturer le paiement PayPal
     */
    public function capturePayPalOrder(Request $request)
    {
        try {
            // Log::info('Capture commande PayPal Nihao Travel', $request->all());

            $request->validate([
                'orderID' => 'required',
                'formData' => 'required|array'
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
            $provider->getAccessToken();

            $result = $provider->capturePaymentOrder($request->orderID);

            // Log::info('Résultat capture PayPal Nihao Travel', $result);

            if (isset($result['status']) && $result['status'] == 'COMPLETED') {
                return $this->createTravelAfterPayment($result, $request);
            } else {
                // Log::error('Capture PayPal Nihao Travel échouée', $result);
                return response()->json([
                    'success' => false,
                    'error' => 'Le paiement n\'a pas pu être capturé'
                ], 400);
            }
        } catch (\Exception $e) {
            // Log::error('Exception capturePayPalOrder Nihao Travel: ' . $e->getMessage(), [
            //     'trace' => $e->getTraceAsString()
            // ]);
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors du traitement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Créer l'enregistrement Travel après paiement réussi
     */
    private function createTravelAfterPayment($paypalResult, $request)
    {
        try {
            // Récupérer les données de la session
            $formData = session('nihao_travel_form_data') ?? $request->formData;
            $amountXOF = session('nihao_travel_amount_xof') ?? 500000;
            $amountUSD = session('nihao_travel_amount_usd');
            $orderID = session('nihao_travel_order_id') ?? $request->orderID;

            // Calculer le montant USD si non disponible
            if (!$amountUSD) {
                $amountUSD = $this->convertToUSD($amountXOF);
            }

            // Vérification avec le montant PayPal
            $paypalAmountUSD = floatval($paypalResult['purchase_units'][0]['payments']['captures'][0]['amount']['value'] ?? 0);

            if ($paypalAmountUSD > 0 && abs($paypalAmountUSD - $amountUSD) > 0.02) {
                // Log::warning('Écart de montant PayPal Nihao Travel', [
                //     'session_usd' => $amountUSD,
                //     'paypal_usd' => $paypalAmountUSD
                // ]);
                $amountUSD = $paypalAmountUSD;
            }

            // Générer un code unique
            $code = 'TRAVEL-' . strtoupper(Str::random(8));

            // Créer l'enregistrement Travel
            $travel = Travel::create([
                'code' => $code,
                'first_name' => $formData['first_name'],
                'last_name' => $formData['last_name'],
                'email' => $formData['email'],
                'phone' => $formData['phone'],
                'company' => $formData['company'] ?? null,
                'canton_edition' => $formData['canton_edition'],
                'additional_info' => $formData['additional_info'] ?? null,
                'amount_xof' => $amountXOF,
                'payment_method' => 'paypal',
                'payment_status' => 'paid',
                'status' => 'pending',
                'paypal_order_id' => $orderID,
                'paypal_transaction_id' => $paypalResult['id'],
                'paypal_details' => $paypalResult
            ]);

            // Log::info('Travel créé avec succès', [
            //     'id' => $travel->id,
            //     'code' => $code,
            //     'montant_xof' => $amountXOF,
            //     'montant_usd' => $amountUSD
            // ]);

            // Envoyer l'email de confirmation
            try {
                Mail::to($travel->email)->send(new NihaoTravelConfirmation($travel));
                // Log::info('Email de confirmation envoyé pour Travel ' . $travel->id);
            } catch (\Exception $e) {
                Log::error('Erreur envoi email Travel: ' . $e->getMessage());
                // Ne pas bloquer la création si l'email échoue
            }

            // Nettoyer la session
            session()->forget([
                'nihao_travel_order_id',
                'nihao_travel_amount_usd',
                'nihao_travel_amount_xof',
                'nihao_travel_form_data'
            ]);

            return response()->json([
                'success' => true,
                'travel_id' => $travel->id,
                'travel_code' => $code,
                'message' => 'Inscription Nihao Travel confirmée avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur création Travel: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la création de l\'inscription: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Page de confirmation
     */
    public function confirmation(Request $request)
    {
        $travelId = $request->query('order_id') ?? $request->query('travel_id');
        $travelCode = $request->query('code');

        $travel = Travel::where(function($query) use ($travelId, $travelCode) {
                if ($travelId) {
                    $query->where('id', $travelId);
                }
                if ($travelCode) {
                    $query->orWhere('code', $travelCode);
                }
            })
            ->first();

        if (!$travel) {
            return redirect()->route('nihao.travel')->with('error', 'Inscription non trouvée');
        }

        return view('front.pages.nihao-travel-confirmation', compact('travel'));
    }

    /**
     * Page d'annulation
     */
    public function cancel(Request $request)
    {
        // Nettoyer la session en cas d'annulation
        session()->forget([
            'nihao_travel_order_id',
            'nihao_travel_amount_usd',
            'nihao_travel_amount_xof',
            'nihao_travel_form_data'
        ]);

        return view('front.pages.nihao-travel-cancel');
    }
}
