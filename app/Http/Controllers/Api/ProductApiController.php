<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductApiController extends Controller
{
    /**
     * GET /api/products/recent?limit=4
     *
     * Retourne les N derniers produits enregistrés (triés par created_at DESC).
     * Équivalent de FrontHelper::fourProducts() côté web.
     */
    public function recent(Request $request)
    {
        $limit = (int) $request->query('limit', 4);
        $limit = max(1, min($limit, 50)); // sécurité : entre 1 et 50

        $products = Produit::with(['sousCategorie.categorie', 'images'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(fn($p) => $this->formatProduct($p));

        return response()->json(['products' => $products]);
    }

    /**
     * GET /api/products/popular?limit=4
     *
     * Retourne les N produits les plus commandés (commandes payées, non annulées).
     * Équivalent de FrontHelper::fourProductsPopulars() côté web.
     */
    public function popular(Request $request)
    {
        $limit = (int) $request->query('limit', 4);
        $limit = max(1, min($limit, 50));

        try {
            // 1. Récupérer les IDs populaires (même requête que fourProductsPopulars)
            $popularIds = DB::table('commanders')
                ->join('commandes', 'commanders.commande_id', '=', 'commandes.id')
                ->join('produits', 'commanders.produit_id', '=', 'produits.id')
                ->select(
                    'commanders.produit_id',
                    DB::raw('SUM(commanders.quantity) as total_quantity'),
                    DB::raw('COUNT(DISTINCT commanders.commande_id) as order_count')
                )
                ->where('commandes.payment_status', 'paid')
                ->where('commandes.status', '!=', 'cancelled')
                ->groupBy('commanders.produit_id')
                ->orderByDesc('total_quantity')
                ->orderByDesc('order_count')
                ->orderByDesc(DB::raw('total_quantity * produits.price'))
                ->limit($limit)
                ->pluck('produit_id')
                ->toArray();

            // 2. Charger les produits populaires dans l'ordre des IDs
            $products = collect();

            if (!empty($popularIds)) {
                $products = Produit::with(['sousCategorie.categorie', 'images'])
                    ->whereIn('id', $popularIds)
                    ->orderByRaw('FIELD(id, ' . implode(',', $popularIds) . ')')
                    ->get();
            }

            // 3. Compléter si moins de $limit produits (fallback sur les plus récents)
            $missing = $limit - $products->count();

            if ($missing > 0) {
                $additional = Produit::with(['sousCategorie.categorie', 'images'])
                    ->whereNotIn('id', $products->pluck('id'))
                    ->orderBy('created_at', 'desc')
                    ->limit($missing)
                    ->get();

                $products = $products->merge($additional);
            }

            return response()->json([
                'products' => $products->map(fn($p) => $this->formatProduct($p)),
            ]);

        } catch (\Exception $e) {
            Log::error('ProductApiController::popular error: ' . $e->getMessage());

            // Fallback : retourner les plus récents
            $products = Produit::with(['sousCategorie.categorie', 'images'])
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(fn($p) => $this->formatProduct($p));

            return response()->json(['products' => $products]);
        }
    }

    /**
     * Formate un Produit Eloquent en tableau JSON pour le mobile.
     * Les prix sont retournés en XOF (devise de base, comme en BD).
     */
    private function formatProduct(Produit $p): array
    {
        return [
            'id'              => $p->id,
            'name'            => $p->name,
            'slug'            => $p->slug,
            'price'           => (float) $p->price,           // XOF
            'original_price'  => $p->original_price
                                    ? (float) $p->original_price
                                    : null,
            'image_main'      => $p->image_main
                                    ? asset($p->image_main)   // URL complète
                                    : null,
            'images'          => $p->images
                                    ? $p->images->map(fn($img) => [
                                          'url' => asset($img->url),
                                      ])->values()->toArray()
                                    : [],
            'color'           => $p->color,
            'niveau_confort'  => $p->niveau_confort,
            'poids'           => $p->poids ? (float) $p->poids : null,
            'description'     => $p->description,
            'sous_categorie'  => $p->sousCategorie ? [
                'label'     => $p->sousCategorie->label,
                'categorie' => $p->sousCategorie->categorie ? [
                    'label' => $p->sousCategorie->categorie->label,
                ] : null,
            ] : null,
            'rating'          => 4.5,   // à remplacer par avg des avis quand disponible
            'review_count'    => 0,     // idem
            'created_at'      => $p->created_at?->toISOString(),
        ];
    }
}
