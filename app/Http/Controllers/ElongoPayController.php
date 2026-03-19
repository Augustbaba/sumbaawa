<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Commande;
use App\Models\Commander;
use App\Models\Pays;
use App\Mail\DeliveryProcessingEmail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class ElongoPayController extends Controller
{

    private function sendOrderEmails($order, $deliveryData)
    {
        try {
            $user = Auth::user();
            Mail::to($user->email)->send(new DeliveryProcessingEmail($order, $deliveryData));
        } catch (\Exception $e) {
            Log::error('Erreur envoi email: ' . $e->getMessage());
        }
    }

    public function capture(Request $request)
    {
        $request->validate([
            'transaction_id'   => 'required|string',
            'amount_xof'       => 'required|numeric|min:1',
            'requiresDelivery' => 'required|boolean',
            'deliveryInfo'     => 'required_if:requiresDelivery,true',
        ]);

        $userId = Auth::id();
        if (!$userId) return response()->json(['success' => false, 'error' => 'Non authentifié'], 401);

        $cart = session('cart', []);
        if (empty($cart)) return response()->json(['success' => false, 'error' => 'Panier vide'], 400);

        // Sécurité : vérifier vs total panier réel
        $totalCart  = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));
        $amountXOF  = $totalCart; // toujours côté serveur

        $code           = 'CMD-' . strtoupper(Str::random(8));
        $address        = null;
        $typeId         = null;
        $deliveryMethod = null;
        $deliveryInfo   = null;
        $deliveryData   = $request->deliveryInfo;

        if ($deliveryData) {
            $deliveryMethod = $deliveryData['deliveryMethod'] ?? null;
            $typeId         = $deliveryData['deliveryType'] ?? null;

            if ($deliveryMethod === 'tinda_awa') {
                $pays    = Pays::find($deliveryData['country']);
                $address = "Livraison Tinda Awa - Pays: " . ($pays ? $pays->name : 'N/A') .
                        ", Ville: {$deliveryData['city']}, Adresse: {$deliveryData['address']}" .
                        ", Destinataire: {$deliveryData['recipientName']}, Tél: {$deliveryData['recipientPhone']}";
            } else {
                $address = "Livraison Cargo - Ville: {$deliveryData['city']}, Adresse: {$deliveryData['address']}" .
                        ", Contact: {$deliveryData['contactName']}, Tél: {$deliveryData['contactPhone']}";
            }

            $deliveryInfo = json_encode($deliveryData);
        }

        $commande = Commande::create([
            'user_id'        => $userId,
            'code'           => $code,
            'address'        => $address,
            'type_id'        => $typeId,
            'delivery_method'=> $deliveryMethod,
            'delivery_info'  => $deliveryInfo,
            'payment_method' => 'elongopay',
            'payment_status' => 'paid',
            'status'         => 'pending',
            'payment_id'     => $request->transaction_id,
            'total_amount'   => $amountXOF,
            'currency'       => 'XOF',
        ]);

        foreach ($cart as $item) {
            Commander::create([
                'commande_id'        => $commande->id,
                'produit_id'         => $item['id'],
                'quantity'           => $item['quantity'],
                'description_produit'=> "Couleur: " . ($item['color'] ?? 'N/A') . ", Poids: " . ($item['poids'] ?? 0) . " kg",
                'unit_price'         => $item['price'],
                'total_price'        => $item['price'] * $item['quantity'],
            ]);
        }

        try {
            $this->sendOrderEmails($commande, $deliveryData);
        } catch (\Exception $e) {
            Log::error('Email error: ' . $e->getMessage());
        }

        session()->forget(['cart', 'pending_delivery_info', 'delivery_info']);

        return response()->json([
            'success'    => true,
            'order_id'   => $commande->id,
            'order_code' => $code,
        ]);
    }
}
