<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Http\Request;

class CategoryProductsApiController extends Controller
{
    /**
     * GET /api/categories/{id}/products
     *      ?page=1
     *      &per_page=10
     *      &sous_categorie_id=3   (optionnel — null = "Tout voir")
     *
     * Retourne les produits paginés d'une catégorie,
     * avec les sous-catégories incluses uniquement à la page 1.
     *
     * Miroir de categories.single côté web.
     */
    public function index(Request $request, int $id)
    {
        $categorie = Categorie::with('sousCategories')->findOrFail($id);

        $perPage       = (int) $request->query('per_page', 10);
        $perPage       = max(1, min($perPage, 50));
        $sousCatId     = $request->query('sous_categorie_id');

        // ── Requête produits ──────────────────────────────────────────────

        $query = Produit::with(['sousCategorie.categorie', 'images'])
            ->whereHas('sousCategorie', function ($q) use ($id) {
                $q->where('categorie_id', $id);
            });

        // Filtre sous-catégorie (équivalent checkbox web)
        if ($sousCatId) {
            $query->where('sous_categorie_id', (int) $sousCatId);
        }

        $paginator = $query->orderBy('created_at', 'desc')->paginate($perPage);

        // ── Formatage produits ────────────────────────────────────────────

        $products = collect($paginator->items())->map(fn($p) => [
            'id'              => $p->id,
            'name'            => $p->name,
            'slug'            => $p->slug ?? '',
            'price'           => (float) $p->price,
            'original_price'  => $p->original_price
                                     ? (float) $p->original_price
                                     : null,
            'image_main'      => $p->image_main ? asset($p->image_main) : null,
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
            'rating'       => 4.5,
            'review_count' => 0,
        ]);

        // ── Sous-catégories (envoyées uniquement page 1) ──────────────────

        $subCategories = [];
        if ($paginator->currentPage() === 1) {
            $subCategories = $categorie->sousCategories
                ->map(fn($s) => ['id' => $s->id, 'label' => $s->label])
                ->values()
                ->toArray();
        }

        return response()->json([
            'products'        => $products,
            'sous_categories' => $subCategories,
            'has_more'        => $paginator->hasMorePages(),
            'total'           => $paginator->total(),
            'page'            => $paginator->currentPage(),
            'per_page'        => $paginator->perPage(),
        ]);
    }
}
