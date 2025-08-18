<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

    public function produitDetail(Produit $produit)
    {
        $produit->load('sousCategorie.categorie', 'images');
        $relatedProduits = Produit::where('sous_categorie_id', $produit->sous_categorie_id)
            ->where('id', '!=', $produit->id)
            ->with(['sousCategorie', 'images'])
            ->take(6)
            ->get();

        return view('front.pages.produits.single', compact('produit', 'relatedProduits'));
    }

    // public function single(Produit $produit)
    // {
    //     $produit->load('sousCategorie.categorie', 'images');
    //     $relatedProduits = Produit::where('sous_categorie_id', $produit->sous_categorie_id)
    //         ->where('id', '!=', $produit->id)
    //         ->with(['sousCategorie', 'images'])
    //         ->take(6)
    //         ->get();

    //     return view('front.pages.produits.single', compact('produit', 'relatedProduits'));
    // }

    public function addToCart(Request $request)
    {
        $productData = $request->input('product');
        $cart = session()->get('cart', []);

        if (isset($cart[$productData['id']])) {
            $cart[$productData['id']]['quantity'] += $productData['quantity'];
        } else {
            $cart[$productData['id']] = [
                'id' => $productData['id'],
                'name' => $productData['name'],
                'image_main' => $productData['image_main'],
                'color' => $productData['color'],
                'niveau_confort' => $productData['niveau_confort'],
                'poids' => $productData['poids'],
                'price' => $productData['price'],
                'quantity' => $productData['quantity'],
                'product_url' => route('produits.single', Str::slug($productData['name']))
            ];
        }

        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        $freeShippingThreshold = 50;
        $progressPercentage = min(($total / $freeShippingThreshold) * 100, 100);

        session()->put('cart', $cart);

        return response()->json([
            'cart' => [
                'items' => array_values($cart),
                'total' => number_format($total, 2),
                'free_shipping_threshold' => $freeShippingThreshold,
                'progress_percentage' => $progressPercentage
            ]
        ]);
    }

    public function updateCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            if ($quantity <= 0) {
                unset($cart[$productId]);
            }
        }

        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        $freeShippingThreshold = 50;
        $progressPercentage = min(($total / $freeShippingThreshold) * 100, 100);

        session()->put('cart', $cart);

        return response()->json([
            'cart' => [
                'items' => array_values($cart),
                'total' => number_format($total, 2),
                'free_shipping_threshold' => $freeShippingThreshold,
                'progress_percentage' => $progressPercentage
            ]
        ]);
    }

    public function removeFromCart(Request $request)
    {
        $productId = $request->input('product_id');
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }

        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        $freeShippingThreshold = 50;
        $progressPercentage = min(($total / $freeShippingThreshold) * 100, 100);

        session()->put('cart', $cart);

        return response()->json([
            'cart' => [
                'items' => array_values($cart),
                'total' => number_format($total, 2),
                'free_shipping_threshold' => $freeShippingThreshold,
                'progress_percentage' => $progressPercentage
            ]
        ]);
    }

    public function clearCart()
    {
        session()->forget('cart');

        return response()->json([
            'cart' => [
                'items' => [],
                'total' => '0.00',
                'free_shipping_threshold' => 50,
                'progress_percentage' => 0
            ]
        ]);
    }

    public function viewCart()
    {
        $cart = session()->get('cart', []);
        $cartItems = array_map(function ($item) {
            $item['product_url'] = route('produits.single', Str::slug($item['name']));
            return $item;
        }, $cart);

        // dd($cartItems);
        return view('front.pages.cart', [
            'cart' => [
                'items' => array_values($cartItems),
                'total' => number_format(array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)), 2),
                'free_shipping_threshold' => 50,
                'progress_percentage' => min((array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)) / 50) * 100, 100)
            ]
        ]);
    }

    public function getCart()
    {
        try {
            $cart = session()->get('cart', []);
            $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
            $freeShippingThreshold = 50;
            $progressPercentage = min(($total / $freeShippingThreshold) * 100, 100);

            return response()->json([
                'cart' => [
                    'items' => array_values($cart),
                    'total' => number_format($total, 2),
                    'free_shipping_threshold' => $freeShippingThreshold,
                    'progress_percentage' => $progressPercentage
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur dans getCart: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur: ' . $e->getMessage()], 500);
        }
    }




}
