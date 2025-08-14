<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class SumbaawaController extends Controller
{
    public function categoriesSingle(Categorie $categorie)
    {
        // Logic to handle single category view
        return view('front.pages.categories.single', compact('categorie'));
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
}
