<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;

class CategoryApiController extends Controller
{
    /**
     * GET /api/categories?page=1&per_page=10
     *
     * Retourne les catégories paginées avec leur image.
     * Miroir de la page categories.index côté web.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 10);
        $perPage = max(1, min($perPage, 50)); // sécurité

        $paginator = Categorie::orderBy('label')
            ->paginate($perPage);

        $categories = collect($paginator->items())->map(fn($c) => [
            'id'        => $c->id,
            'label'     => $c->label,
            'slug'      => $c->slug ?? '',
            'image_url' => $c->image ? asset($c->image) : null,
        ]);

        return response()->json([
            'categories' => $categories,
            'has_more'   => $paginator->hasMorePages(),
            'total'      => $paginator->total(),
            'page'       => $paginator->currentPage(),
            'per_page'   => $paginator->perPage(),
        ]);
    }
}
