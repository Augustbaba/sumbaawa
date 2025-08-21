<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\SousCategorie;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProduitController extends Controller
{
    public function index()
    {
        $produits = Produit::with('sousCategorie', 'images')->latest()->paginate(10);
        return view('back.pages.produits.index', compact('produits'));
    }

    public function create()
    {
        $subcategories = SousCategorie::all();
        return view('back.pages.produits.create', compact('subcategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sous_categorie_id' => 'required|exists:sous_categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:produits,slug',
            'description' => 'nullable|string',
            'image_main' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'color' => 'nullable|string|max:50',
            'niveau_confort' => 'nullable|integer|between:1,5',
            'poids' => 'nullable|numeric|min:0',
            'secondary_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Génération du slug si vide
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Enregistrement de l'image principale
        $validated['image_main'] = $request->file('image_main')->store('produits/main', 'public');

        // Création du produit
        $produit = Produit::create($validated);

        // Enregistrement des images secondaires
        if ($request->hasFile('secondary_images')) {
            foreach ($request->file('secondary_images') as $image) {
                $path = $image->store('produits/secondary', 'public');
                
                Image::create([
                    'produit_id' => $produit->id,
                    'url' => $path,
                    'reference' => 'image_'.time().'_'.rand(1000,9999)
                ]);
            }
        }

        return redirect()->route('produits.index')
                         ->with('success', 'Produit créé avec succès!');
    }

    public function show(Produit $produit)
    {
        return view('back.pages.produits.show', compact('produit'));
    }

    public function edit(Produit $produit)
    {
        $subcategories = SousCategorie::all();
        return view('back.pages.produits.edit', compact('produit', 'subcategories'));
    }

    public function update(Request $request, Produit $produit)
    {
        $validated = $request->validate([
            'sous_categorie_id' => 'required|exists:sous_categories,id',
            'name' => 'required|string|max:255|unique:produits,name,'.$produit->id,
            'slug' => 'nullable|string|max:255|unique:produits,slug,'.$produit->id,
            'description' => 'nullable|string',
            'image_main' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'color' => 'nullable|string|max:50',
            'niveau_confort' => 'nullable|integer|between:1,5',
            'poids' => 'nullable|numeric|min:0',
            'secondary_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Gestion de l'image principale
        if ($request->hasFile('image_main')) {
            // Supprimer l'ancienne image
            Storage::disk('public')->delete($produit->image_main);
            $validated['image_main'] = $request->file('image_main')->store('produits/main', 'public');
        }

        $produit->update($validated);

        // Gestion des images secondaires
        if ($request->hasFile('secondary_images')) {
            foreach ($request->file('secondary_images') as $image) {
                $path = $image->store('produits/secondary', 'public');
                
                Image::create([
                    'produit_id' => $produit->id,
                    'url' => $path,
                    'reference' => 'image_'.time().'_'.rand(1000,9999)
                ]);
            }
        }

        return redirect()->route('produits.index')
                         ->with('success', 'Produit mis à jour avec succès!');
    }

    public function destroy(Produit $produit)
    {
        // Supprimer l'image principale
        Storage::disk('public')->delete($produit->image_main);

        // Supprimer les images secondaires
        foreach ($produit->images as $image) {
            Storage::disk('public')->delete($image->url);
            $image->delete();
        }

        $produit->delete();

        return redirect()->route('produits.index')
                         ->with('success', 'Produit supprimé avec succès!');
    }
}