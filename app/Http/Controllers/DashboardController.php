<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function dashboard()
    {
        $user = Auth::user();
        $commandes = Commande::with(['type', 'commandesProduits.produit'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('back.pages.index', compact('commandes'));
    }

    public function show($code)
    {
        $commande = Commande::with([
            'type',
            'commandesProduits.produit'
        ])->where('code', $code)
          ->where('user_id', Auth::id())
          ->firstOrFail();

        // Décoder les infos de livraison si présentes
        if ($commande->delivery_info) {
            $commande->delivery_info = json_decode($commande->delivery_info, true);
        }

        return view('back.pages.users.orders.show', compact('commande'));
    }

    /**
     * Marquer une commande comme "récupérée" (seulement si status = delivered)
     */
    public function markAsReceived($code)
    {

        $commande = Commande::where('code', $code)
            ->where('user_id', Auth::id())
            ->where('status', 'delivered')
            ->orWhere('status', 'picked_up')
            ->firstOrFail();

        $commande->update([
            'status' => 'delivered',
            'is_received' => true,
            'received_at' => now()
        ]);

        $this->notificationService->notifyOrderReceived($commande);

        return redirect()->back()->with('success', 'Commande marquée comme récupérée avec succès.');
    }
}
