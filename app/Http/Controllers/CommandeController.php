<?php

namespace App\Http\Controllers;

use App\Mail\OrderStatusUpdateEmail;
use App\Mail\ShippingFeeEmail;
use App\Models\Commande;
use App\Models\Type;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CommandeController extends Controller
{
    /**
     * Afficher la liste des commandes
     */
    public function index()
    {
        $commandes = Commande::with(['user', 'type', 'commandesProduits.produit'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('back.pages.commandes.index', compact('commandes'));
    }

    /**
     * Afficher les détails d'une commande
     */
    public function show(Commande $commande)
    {
        $commande->load(['user', 'type', 'commandesProduits.produit']);

        // Décoder les infos de livraison si présentes
        if ($commande->delivery_info) {
            $commande->delivery_info = json_decode($commande->delivery_info, true);
        }

        // Récupérer les types de transport pour le formulaire
        $types = Type::all();

        return view('back.pages.commandes.show', compact('commande', 'types'));
    }

    /**
     * Mettre à jour le statut d'une commande
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,ready_pickup,picked_up,in_transit,arrived,delivered,cancelled'
        ]);

        $commande = Commande::with('user')->findOrFail($id);
        $oldStatus = $commande->status;
        $commande->status = $request->status;
        $commande->save();

        // Envoyer un email de notification de changement de statut
        $this->sendStatusUpdateEmail($commande, $oldStatus);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès',
                'status_label' => $commande->status_label
            ]);
        }
        return redirect()->back()->with('success', 'Statut mis à jour avec succès');
    }

    /**
     * Sauvegarder les frais de livraison
     */
    public function saveShippingFees(Request $request, $id)
    {
        $request->validate([
            'shipping_fee' => 'required|numeric|min:0',
            'estimated_delivery' => $request->delivery_method == 'tinda_awa' ? 'required|date' : 'nullable',
            'observations' => 'nullable|string'
        ]);

        $commande = Commande::with('user')->findOrFail($id);

        // Vérifier que la commande est prête pour les frais de livraison
        if ($commande->status !== 'ready_pickup') {
            return response()->json([
                'success' => false,
                'message' => 'La commande doit être en statut "Prêt pour retrait" pour définir les frais de livraison'
            ], 400);
        }

        // Mettre à jour les informations de livraison
        $commande->shipping_fee = $request->shipping_fee;
        $commande->observations = $request->observations;

        if ($request->delivery_method == 'tinda_awa' && $request->estimated_delivery) {
            $commande->estimated_delivery = $request->estimated_delivery;
        }

        $commande->shipping_status = 'fee_pending';
        $commande->save();

        // Envoyer l'email avec les frais de livraison
        $this->sendShippingFeeEmail($commande);

        return response()->json([
            'success' => true,
            'message' => 'Frais de livraison enregistrés et email envoyé au client'
        ]);
    }

    /**
     * Envoyer un email de frais de livraison
     */
    private function sendShippingFeeEmail($commande)
    {
        try {
            $user = $commande->user;

            Mail::to($user->email)->send(new ShippingFeeEmail($commande));


        } catch (\Exception $e) {
            Log::error('Erreur envoi email frais de livraison: ' . $e->getMessage());
        }
    }

    /**
     * Envoyer un email de mise à jour de statut
     */
    private function sendStatusUpdateEmail($commande, $oldStatus)
    {
        try {
            $user = $commande->user;

            Mail::to($user->email)->send(new OrderStatusUpdateEmail($commande, $oldStatus));


        } catch (\Exception $e) {
            Log::error('Erreur envoi email statut: ' . $e->getMessage());
        }
    }

    /**
     * Exporter les commandes en CSV
     */
    public function export(Request $request)
    {
        $commandes = Commande::with(['user', 'type'])
            ->orderBy('created_at', 'desc')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=commandes_' . date('Y-m-d') . '.csv',
        ];

        $callback = function() use ($commandes) {
            $file = fopen('php://output', 'w');

            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Code',
                'Client',
                'Email',
                'Montant Total',
                'Frais Livraison',
                'Méthode Livraison',
                'Statut',
                'Statut Paiement',
                'Date Commande'
            ]);

            // Données
            foreach ($commandes as $commande) {
                fputcsv($file, [
                    $commande->id,
                    $commande->code,
                    $commande->user->name,
                    $commande->user->email,
                    number_format($commande->total_amount, 2, ',', ' '),
                    $commande->shipping_fee ? number_format($commande->shipping_fee, 2, ',', ' ') : 'N/A',
                    $commande->delivery_method == 'tinda_awa' ? 'Tinda Awa' : ($commande->delivery_method == 'cargo' ? 'Cargo' : 'N/A'),
                    $commande->status_label,
                    $commande->payment_status == 'paid' ? 'Payé' : 'En attente',
                    $commande->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Statistiques des commandes
     */
    public function stats()
    {
        $stats = [
            'total' => Commande::count(),
            'pending' => Commande::where('status', 'pending')->count(),
            'processing' => Commande::where('status', 'processing')->count(),
            'ready_pickup' => Commande::where('status', 'ready_pickup')->count(),
            'delivered' => Commande::where('status', 'delivered')->count(),
            'cancelled' => Commande::where('status', 'cancelled')->count(),
            'total_amount' => Commande::sum('total_amount'),
            'total_shipping' => Commande::whereNotNull('shipping_fee')->sum('shipping_fee'),
            'today_orders' => Commande::whereDate('created_at', today())->count()
        ];

        // Commandes récentes
        $recentOrders = Commande::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('back.pages.commandes.stats', compact('stats', 'recentOrders'));
    }
}
