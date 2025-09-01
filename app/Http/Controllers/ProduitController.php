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
        // dd('good');
        $produits = Produit::with('sousCategorie', 'images')->latest()->paginate(10);
        return view('back.pages.produits.index', compact('produits'));
    }

    public function create()
    {
        $sousCategories = SousCategorie::all();
        $colors = ['Noir', 'Blanc', 'Bleu', 'Rouge', 'Vert', 'Jaune', 'Gris']; // Predefined colors
        return view('back.pages.produits.create', compact('sousCategories', 'colors'));
    }

public function store(Request $request)
{
    \Log::info('Début de la méthode store');

    try {
        $validated = $request->validate([
            'sous_categorie_id' => 'required|exists:sous_categories,id',
            'name' => 'required|string|max:255|unique:produits,name',
            'description' => 'nullable|string',
            'image_main' => 'required|image|mimes:jpeg,png,jpg,gif|max:100240',
            'price' => 'required|numeric|min:0',
            'colors' => 'nullable|array',
            'colors.*' => 'string|in:Noir,Blanc,Bleu,Rouge,Vert,Jaune,Gris',
            'niveau_confort' => 'nullable|integer|between:1,5',
            'poids' => 'nullable|numeric|min:0',
            'secondary_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:100240',
        ]);

        \Log::info('Validation passée');

        $slug = Str::slug($validated['name']);
        $count = Produit::where('slug', 'like', $slug . '%')->count();
        $validated['slug'] = $count ? "{$slug}-{$count}" : $slug;

        $validated['color'] = !empty($validated['colors']) ? implode(',', $validated['colors']) : null;

        // Upload image principale
        if ($request->hasFile('image_main')) {
            $validated['image_main'] = $request->file('image_main')->store('produits/main', 'public');
        }

        $produit = Produit::create($validated);

        // Upload images secondaires
        if ($request->hasFile('secondary_images')) {
            foreach ($request->file('secondary_images') as $image) {
                if ($image->isValid()) {
                    $path = $image->store('produits/secondary', 'public');
                    Image::create([
                        'produit_id' => $produit->id,
                        'url' => $path,
                        'reference' => 'image_' . time() . '_' . rand(1000, 9999),
                    ]);
                }
            }
        }

        return redirect()->route('produits.index')->with('success', 'Produit créé avec succès!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Erreur de validation:', ['errors' => $e->errors()]);
        return redirect()->back()
                        ->withErrors($e->errors())
                        ->withInput($request->all());

    } catch (\Exception $e) {
        \Log::error('Erreur générale:', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        return redirect()->back()
                        ->with('error', 'Erreur: ' . $e->getMessage())
                        ->withInput($request->all());
    }
}


    public function show($id)
    {
        $produit = Produit::with('images')->findOrFail($id);
        return view('back.pages.produits.show', compact('produit'));
    }

    public function edit($id)
    {
        $produit = Produit::findOrFail($id);
        $sousCategories = SousCategorie::all();
        $colors = ['Noir', 'Blanc', 'Bleu', 'Rouge', 'Vert', 'Jaune', 'Gris'];
        return view('back.pages.produits.edit', compact('produit', 'sousCategories', 'colors'));
    }

    public function update(Request $request, $id)
    {
        $produit = Produit::findOrFail($id);
        $validated = $request->validate([
            'sous_categorie_id' => 'required|exists:sous_categories,id',
            'name' => 'required|string|max:255|unique:produits,name,' . $produit->id,
            'description' => 'nullable|string',
            'image_main' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'colors' => 'nullable|array',
            'colors.*' => 'string|in:Noir,Blanc,Bleu,Rouge,Vert,Jaune,Gris',
            'niveau_confort' => 'nullable|integer|between:1,5',
            'poids' => 'nullable|numeric|min:0',
            'secondary_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Auto-generate unique slug
        $slug = Str::slug($validated['name']);
        $count = Produit::where('slug', 'like', $slug . '%')->where('id', '!=', $produit->id)->count();
        $validated['slug'] = $count ? "{$slug}-{$count}" : $slug;

        // Handle colors
        $validated['color'] = !empty($validated['colors']) ? implode(',', $validated['colors']) : null;

        // Handle main image
        if ($request->hasFile('image_main')) {
            if ($produit->image_main) {
                Storage::disk('public')->delete($produit->image_main);
            }
            $validated['image_main'] = $request->file('image_main')->store('produits/main', 'public');
        } else {
            $validated['image_main'] = $produit->image_main;
        }

        $produit->update($validated);

        // Handle secondary images
        if ($request->hasFile('secondary_images')) {
            foreach ($request->file('secondary_images') as $image) {
                $path = $image->store('produits/secondary', 'public');
                Image::create([
                    'produit_id' => $produit->id,
                    'url' => $path,
                    'reference' => 'image_' . time() . '_' . rand(1000, 9999),
                ]);
            }
        }

        return redirect()->route('produits.index')
                         ->with('success', 'Produit mis à jour avec succès!');
    }

    public function destroy($id)
    {
        $produit = Produit::findOrFail($id);

        // Delete main image
        if ($produit->image_main) {
            Storage::disk('public')->delete($produit->image_main);
        }

        // Delete secondary images
        foreach ($produit->images as $image) {
            Storage::disk('public')->delete($image->url);
            $image->delete();
        }

        $produit->delete();

        return redirect()->route('produits.index')
                         ->with('success', 'Produit supprimé avec succès!');
    }
}
