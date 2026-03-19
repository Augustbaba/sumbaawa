<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Commander;
use App\Mail\DeliveryProcessingEmail;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

/**
 * Miroir mobile du CheckoutController web.
 * Gère le flow PayPal pour l'app Flutter :
 *   POST /api/paypal/create-order   → approvalUrl
 *   POST /api/paypal/capture-order  → crée la commande en BD
 */
class PayPalMobileApiController extends Controller
{
    // ── Convertir XOF → USD (identique à la méthode web) ──────────────────────

    private function convertToUSD(float $amountXOF): float
    {
        $usdCurrency = Currency::where('code', 'USD')->first();
        $usdRate     = $usdCurrency?->exchange_rate ?? 600;
        return round($amountXOF / $usdRate, 2);
    }

    // ── Initialiser le client PayPal ──────────────────────────────────────────

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

    // ── POST /api/paypal/create-order ─────────────────────────────────────────
    //
    // Reçoit le total XOF + infos livraison depuis Flutter.
    // Crée la commande PayPal, retourne l'approvalUrl.

    public function createOrder(Request $request)
    {
        $validated = $request->validate([
            'amount_xof'      => 'required|numeric|min:1',
            'delivery_method' => 'required|in:tinda_awa,cargo',
            'shipping'        => 'required|array',
            // shipping = le delivery_info complet (structure identique au web)
            'items'           => 'required|array|min:1',
        ]);

        try {
            $amountXOF = (float) $validated['amount_xof'];
            $amountUSD = $this->convertToUSD($amountXOF);

            if ($amountUSD < 0.01) {
                return response()->json([
                    'success' => false,
                    'message' => 'Montant trop faible pour PayPal (min $0.01)',
                ], 400);
            }

            $provider = $this->makeProvider();

            // Custom scheme intercepté par le WebView Flutter (pas de page web chargée)
            $returnUrl = 'sumbaawa://paypal/success';
            $cancelUrl = 'sumbaawa://paypal/cancel';

            $order = $provider->createOrder([
                'intent'         => 'CAPTURE',
                'purchase_units' => [[
                    'amount'      => [
                        'currency_code' => 'USD',
                        'value'         => number_format($amountUSD, 2, '.', ''),
                    ],
                    'description' => 'Achat sur ' . config('app.name'),
                    'custom_id'   => json_encode([
                        'amount_xof'      => $amountXOF,
                        'user_id'         => Auth::id(),
                        'delivery_method' => $validated['delivery_method'],
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
                Log::error('PayPalMobile createOrder failed', $order);
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création de la commande PayPal',
                ], 500);
            }

            // Trouver l'approvalUrl dans les liens
            $approvalUrl = collect($order['links'])
                ->firstWhere('rel', 'approve')['href'] ?? null;

            // Pas de stockage en session — Flutter renverra toutes les données dans captureOrder

            Log::info('PayPalMobile createOrder OK', [
                'paypal_id'  => $order['id'],
                'amount_xof' => $amountXOF,
                'amount_usd' => $amountUSD,
            ]);

            return response()->json([
                'success'        => true,
                'paypal_order_id'=> $order['id'],
                'approval_url'   => $approvalUrl,
            ]);

        } catch (\Exception $e) {
            Log::error('PayPalMobile createOrder exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ── POST /api/paypal/capture-order ────────────────────────────────────────
    //
    // Appelé après approbation PayPal.
    // Capture le paiement + crée la commande en BD (même logique que le web).

    public function captureOrder(Request $request)
    {
        $request->validate([
            'paypal_order_id' => 'required|string',
            'amount_xof'      => 'required|numeric|min:1',
            // Données renvoyées directement par Flutter (pas de session)
            'delivery_method' => 'required|in:tinda_awa,cargo',
            'shipping'        => 'required|array',
            // shipping = le delivery_info complet (structure identique au web)
            'items'           => 'required|array|min:1',
        ]);

        try {
            $provider = $this->makeProvider();
            $result   = $provider->capturePaymentOrder($request->paypal_order_id);

            Log::info('PayPalMobile captureOrder result', ['status' => $result['status'] ?? 'unknown']);

            if (($result['status'] ?? '') !== 'COMPLETED') {
                return response()->json([
                    'success' => false,
                    'message' => 'Capture PayPal échouée',
                ], 400);
            }

            // Données lues directement depuis la requête Flutter (pas de session)
            $amountXOF      = (float) $request->amount_xof;
            $amountUSD      = (float) ($result['purchase_units'][0]['payments']['captures'][0]['amount']['value'] ?? 0);
            $shipping       = $request->shipping;
            $deliveryMethod = $request->delivery_method;
            $cartItems      = $request->items;

            // Créer la commande en BD
            DB::beginTransaction();

            $code = 'CMD-' . strtoupper(Str::random(8));

            // Construire l'adresse et récupérer type_id — miroir exact de createOrderAfterPayment web
            $typeId  = $shipping['deliveryType'] ?? null;
            $address = null;

            if ($deliveryMethod === 'tinda_awa') {
                $pays    = \App\Models\Pays::find($shipping['country'] ?? null);
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

            $commande = Commande::create([
                'user_id'         => Auth::id(),
                'code'            => $code,
                'address'         => $address,
                'type_id'         => $typeId,           // type de transport
                'delivery_method' => $deliveryMethod,
                'delivery_info'   => json_encode($shipping), // structure identique au web
                'payment_method'  => 'paypal',
                'payment_status'  => 'paid',
                'status'          => 'pending',
                'payment_id'      => $result['id'],
                'payment_email'   => $result['payer']['email_address'] ?? null,
                'total_amount'    => $amountXOF,
                'amount_usd'      => $amountUSD,
                'currency'        => 'XOF',
            ]);

            foreach ($cartItems as $item) {
                $description = "Couleur: " . ($item['color'] ?? 'N/A') .
                              ", Niveau confort: " . ($item['niveau_confort'] ?? 'N/A') .
                              ", Poids: " . ($item['poids'] ?? 0) . " kg";

                Commander::create([
                    'commande_id'        => $commande->id,
                    'produit_id'         => $item['product_id'],
                    'quantity'           => $item['quantity'],
                    'description_produit'=> $description,
                    'unit_price'         => $item['price'],
                    'total_price'        => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();

            // Construire $deliveryData avant de vider la session
            $deliveryData = [
                'deliveryMethod' => $deliveryMethod,
                'name'           => $shipping['name']    ?? '',
                'phone'          => $shipping['phone']   ?? '',
                'city'           => $shipping['city']    ?? '',
                'address'        => $shipping['address'] ?? '',
                'note'           => $shipping['note']    ?? '',
            ];

            // Pas de session à nettoyer — les données viennent directement de la requête

            Log::info('PayPalMobile order created', [
                'id'         => $commande->id,
                'code'       => $code,
                'amount_xof' => $amountXOF,
                'amount_usd' => $amountUSD,
            ]);

            // Envoi email (même logique que le web — ne bloque pas la réponse)
            try {
                Mail::to(Auth::user()->email)
                    ->send(new DeliveryProcessingEmail($commande, $deliveryData));
            } catch (\Exception $mailException) {
                Log::error('PayPalMobile email error: ' . $mailException->getMessage());
            }

            return response()->json([
                'success'        => true,
                'order_id'       => $commande->id,
                'order_code'     => $code,
                'transaction_id' => $result['id'],
                'message'        => 'Commande enregistrée avec succès',
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PayPalMobile captureOrder exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du traitement: ' . $e->getMessage(),
            ], 500);
        }
    }
}
