<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Currency;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

// ─────────────────────────────────────────────────────────────────────────────
//  OrderMobileApiController
//  Miroir exact du DashboardController web
//
//  GET  /api/orders               → liste paginée (10/page)
//  GET  /api/orders/{code}        → détail
//  PUT  /api/orders/{code}/received → marquer comme récupérée
// ─────────────────────────────────────────────────────────────────────────────

class OrderMobileApiController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * GET /api/orders?page=1&per_page=10&status=pending
     * Miroir de DashboardController::dashboard()
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 10);
        $perPage = max(1, min($perPage, 50));
        $status  = $request->query('status');

        $query = Commande::with(['commandesProduits.produit'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        $paginator = $query->paginate($perPage);

        $orders = collect($paginator->items())->map(
            fn($c) => $this->formatOrder($c)
        );

        return response()->json([
            'orders'   => $orders,
            'has_more' => $paginator->hasMorePages(),
            'total'    => $paginator->total(),
            'page'     => $paginator->currentPage(),
        ]);
    }

    /**
     * GET /api/orders/{code}
     * Miroir de DashboardController::show()
     */
    public function show(string $code)
    {
        $commande = Commande::with(['commandesProduits.produit'])
            ->where('code', $code)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($commande->delivery_info) {
            $commande->delivery_info = json_decode($commande->delivery_info, true);
        }

        return response()->json([
            'order' => $this->formatOrder($commande),
        ]);
    }

    /**
     * PUT /api/orders/{code}/received
     * Miroir de DashboardController::markAsReceived()
     */
    public function markAsReceived(string $code)
    {
        $commande = Commande::where('code', $code)
            ->where('user_id', Auth::id())
            ->where(function ($q) {
                $q->where('status', 'delivered')
                  ->orWhere('status', 'picked_up');
            })
            ->firstOrFail();

        $commande->update([
            'status'      => 'delivered',
            'is_received' => true,
            'received_at' => now(),
        ]);

        $this->notificationService->notifyOrderReceived($commande);

        return response()->json([
            'success' => true,
            'message' => 'Commande marquée comme récupérée.',
        ]);
    }

    // ── Formatage commande ────────────────────────────────────────────────────

    private function formatOrder(Commande $c): array
    {
        $items = $c->commandesProduits->map(fn($p) => [
            'id'              => $p->id,
            'name'            => $p->produit?->name ?? $p->name ?? 'Produit supprimé',
            'unit_price'      => (float) $p->unit_price,
            'total_price'     => (float) $p->total_price,
            'quantity'        => $p->quantity,
            'description_produit' => $p->description_produit,
            'product_image'   => $p->produit?->image_main
                                    ? asset($p->produit->image_main)
                                    : null,
        ])->values()->toArray();

        return [
            'id'              => $c->id,
            'code'            => $c->code,
            'status'          => $c->status,
            'status_label'    => $c->status_label,      // accessor sur le modèle
            'payment_status'  => $c->payment_status,
            'payment_method'  => $c->payment_method ?? '',
            'payment_id'      => $c->payment_id,
            'total_amount'    => (float) $c->total_amount,
            'amount_usd'      => $c->amount_usd ? (float) $c->amount_usd : null,
            'delivery_method' => $c->delivery_method ?? '',
            'address'         => $c->address,
            'delivery_info'   => $c->delivery_info,     // déjà décodé si show()
            'shipping_fee'    => $c->shipping_fee ? (float) $c->shipping_fee : null,
            'shipping_status' => $c->shipping_status ?? 'none',
            'is_received'     => (bool) $c->is_received,
            'received_at'     => $c->received_at,
            'created_at'      => $c->created_at->toDateTimeString(),
            'items'           => $items,
        ];
    }
}




