<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SumbaawaController extends Controller
{
    public function categoriesSingle(Categorie $categorie)
    {
        $subcategoryIds = $categorie->sousCategories->pluck('id')->toArray();

        $produits = Produit::whereIn('sous_categorie_id', $subcategoryIds)
            ->with(['sousCategorie', 'images'])
            ->paginate(12); // Remettez à 12 ou votre valeur standard, au lieu de 2 pour les tests

        // Définir l'URL de base pour les liens de pagination sur la route de filtrage
        $produits->setPath(route('categories.filter', $categorie));

        // Ajouter les paramètres subcategories[] pour préserver l'état "Voir tout"
        $produits->appends(['subcategories' => $subcategoryIds]);

        return view('front.pages.categories.single', compact('categorie', 'produits'));
    }

    public function categoriesPage()
    {
        $categories = Categorie::orderBy('label', 'asc')->paginate(12);
        return view('front.pages.categories.index', compact('categories'));
    }

    public function produitDetail($produit)
    {
        // Logic to handle product detail view
    }

    public function sousCategoriesDetail($sousCategorie)
    {
        // Logic to handle sub-category product view
    }

    public function filter(Request $request, Categorie $categorie)
    {
        try {
            // Récupère les IDs des sous-catégories depuis la requête
            $subcategoryIds = $request->input('subcategories', []);

            // Convertit en tableau si ce n'est pas déjà le cas
            if (!is_array($subcategoryIds)) {
                $subcategoryIds = explode(',', $subcategoryIds);
            }

            // Vérifie que les IDs sont valides (entiers)
            $subcategoryIds = array_filter($subcategoryIds, 'is_numeric');

            // Charge les produits avec les relations nécessaires
            if (empty($subcategoryIds)) {
                $produits = Produit::whereIn('sous_categorie_id', $categorie->sousCategories->pluck('id'))
                    ->with(['sousCategorie', 'images'])
                    ->paginate(12);
            } else {
                $produits = Produit::whereIn('sous_categorie_id', $subcategoryIds)
                    ->with(['sousCategorie', 'images'])
                    ->paginate(12);
            }

            // Retourne la vue partielle pour les produits et la pagination
            return view('front.partials.produits', compact('produits'))->render();
        } catch (\Exception $e) {
            Log::error('Erreur dans filter: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors du filtrage des produits'], 500);
        }
    }

}
