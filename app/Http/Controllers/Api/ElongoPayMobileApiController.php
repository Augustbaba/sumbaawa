<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Commander;
use App\Models\Pays;
use App\Mail\DeliveryProcessingEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Miroir mobile de ElongoPayController web.
 * Gère la capture du paiement ElongoPay pour l'app Flutter.
 *
 * POST /api/elongopay/capture
 *
 * Appelé par Flutter après que ElongoPayWebViewScreen reçoit ELONGOPAY_SUCCESS.
 * Même logique exacte que ElongoPayController::capture() du web.
 */
class ElongoPayMobileApiController extends Controller
{
    public function capture(Request $request)
    {
        $request->validate([
            'transaction_id'  => 'required|string',
            'amount_xof'      => 'required|numeric|min:1',
            'delivery_method' => 'required|in:tinda_awa,cargo',
            'shipping'        => 'required|array',   // delivery_info complet (même structure que web)
            'items'           => 'required|array|min:1',
        ]);

        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Non authentifié'], 401);
        }

        try {
            DB::beginTransaction();

            $shipping       = $request->shipping;
            $deliveryMethod = $request->delivery_method;
            $cartItems      = $request->items;

            // Construire adresse + récupérer type_id — miroir exact du web ElongoPayController
            $code    = 'CMD-' . strtoupper(Str::random(8));
            $typeId  = $shipping['deliveryType'] ?? null;
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

            $amountXOF = (float) $request->amount_xof;

            $commande = Commande::create([
                'user_id'         => $userId,
                'code'            => $code,
                'address'         => $address,
                'type_id'         => $typeId,
                'delivery_method' => $deliveryMethod,
                'delivery_info'   => json_encode($shipping),  // identique au web
                'payment_method'  => 'elongopay',
                'payment_status'  => 'paid',
                'status'          => 'pending',
                'payment_id'      => $request->transaction_id,
                'total_amount'    => $amountXOF,
                'currency'        => 'XOF',
            ]);

            foreach ($cartItems as $item) {
                Commander::create([
                    'commande_id'         => $commande->id,
                    'produit_id'          => $item['product_id'],
                    'quantity'            => $item['quantity'],
                    'description_produit' => "Couleur: " . ($item['color'] ?? 'N/A') .
                                            ", Niveau confort: " . ($item['niveau_confort'] ?? 'N/A') .
                                            ", Poids: " . ($item['poids'] ?? 0) . " kg",
                    'unit_price'          => $item['price'],
                    'total_price'         => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();

            // Email — même logique que le web
            try {
                $deliveryData = array_merge($shipping, ['deliveryMethod' => $deliveryMethod]);
                Mail::to(Auth::user()->email)
                    ->send(new DeliveryProcessingEmail($commande, $deliveryData));
            } catch (\Exception $e) {
                Log::error('ElongoPayMobile email error: ' . $e->getMessage());
            }

            Log::info('ElongoPayMobile capture OK', [
                'commande_id'    => $commande->id,
                'transaction_id' => $request->transaction_id,
                'amount_xof'     => $amountXOF,
            ]);

            return response()->json([
                'success'    => true,
                'order_id'   => $commande->id,
                'order_code' => $code,
                'message'    => 'Commande enregistrée avec succès',
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ElongoPayMobile capture exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du traitement: ' . $e->getMessage(),
            ], 500);
        }
    }
}
