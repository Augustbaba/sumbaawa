<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\SousCategorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SousCategorieController extends Controller
{
    /**
     * Affiche la liste des sous-catégories
     */
    public function index()
    {
        $sousCategories = SousCategorie::with('categorie')->latest()->paginate(10);
        return view('back.pages.sous_categories.index', compact('sousCategories'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $categories = Categorie::all(); // Modification ici - plus de filtre sur parent_id
        return view('back.pages.sous_categories.create', compact('categories'));
    }

    /**
     * Enregistre une nouvelle sous-catégorie
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'categorie_id' => 'required|exists:categories,id',
            'label' => 'required|string|max:255|unique:sous_categories,label',
            'slug' => 'nullable|string|max:255|unique:sous_categories,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Génération du slug si vide
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['label']);
        }

        // Gestion de l'image
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('sous_categories', 'public');
        }

        SousCategorie::create($validated);

        return redirect()->route('sous-categories.index')
                         ->with('success', 'Sous-catégorie créée avec succès!');
    }

    /**
     * Affiche une sous-catégorie spécifique
     */
    public function show(SousCategorie $sousCategorie)
    {
        return view('back.pages.sous_categories.show', compact('sousCategorie'));
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(SousCategorie $sousCategorie)
    {
        $categories = Categorie::all();
        return view('back.pages.sous_categories.edit', compact('sousCategorie', 'categories'));
    }

    /**
     * Met à jour une sous-catégorie
     */
    public function update(Request $request, SousCategorie $sousCategorie)
    {
        $validated = $request->validate([
            'categorie_id' => 'required|exists:categories,id',
            'label' => 'required|string|max:255|unique:sous_categories,label,'.$sousCategorie->id,
            'slug' => 'nullable|string|max:255|unique:sous_categories,slug,'.$sousCategorie->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Gestion de l'image
        if ($request->hasFile('image')) {
            // Supprime l'ancienne image si elle existe
            if ($sousCategorie->image) {
                Storage::disk('public')->delete($sousCategorie->image);
            }
            $validated['image'] = $request->file('image')->store('sous_categories', 'public');
        }

        $sousCategorie->update($validated);

        return redirect()->route('sous-categories.index')
                         ->with('success', 'Sous-catégorie mise à jour avec succès!');
    }

    /**
     * Supprime une sous-catégorie
     */
    public function destroy(SousCategorie $sousCategorie)
    {
        // Supprime l'image associée si elle existe
        if ($sousCategorie->image) {
            Storage::disk('public')->delete($sousCategorie->image);
        }

        $sousCategorie->delete();

        return redirect()->route('sous-categories.index')
                         ->with('success', 'Sous-catégorie supprimée avec succès!');
    }
}