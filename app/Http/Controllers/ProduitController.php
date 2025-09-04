<?php
namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\SousCategorie;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ProduitController extends Controller
{
    public function index()
    {
        $produits = Produit::with('sousCategorie', 'images')->latest()->paginate(10);
        return view('back.pages.produits.index', compact('produits'));
    }

    public function create()
    {
        $sousCategories = SousCategorie::all();
        $colors = ['Noir', 'Blanc', 'Bleu', 'Rouge', 'Vert', 'Jaune', 'Gris'];
        return view('back.pages.produits.create', compact('sousCategories', 'colors'));
    }

    public function store(Request $request)
    {
        Log::info('Début de la méthode store');

        try {
            $validated = $request->validate([
                'sous_categorie_id' => 'required|exists:sous_categories,id',
                'name' => 'required|string|max:255|unique:produits,name',
                'description' => 'nullable|string',
                'image_main' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'price' => 'required|numeric|min:0',
                'colors' => 'nullable|array|max:5',
                'colors.*' => 'string|in:Noir,Blanc,Bleu,Rouge,Vert,Jaune,Gris',
                'niveau_confort' => 'nullable|integer|between:1,5',
                'poids' => 'nullable|numeric|min:0',
                'secondary_images' => 'nullable|array|max:5',
                'secondary_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            Log::info('Validation passée', ['input' => $request->except(['image_main', 'secondary_images'])]);

            $slug = Str::slug($validated['name']) . '-' . Str::random(5);
            $count = Produit::where('slug', 'like', $slug . '%')->count();
            $validated['slug'] = $count ? "{$slug}-{$count}" : $slug;

            $validated['color'] = !empty($validated['colors'])
                ? implode(',', array_map('trim', array_map('strtolower', $validated['colors'])))
                : null;

            if ($request->hasFile('image_main') && $request->file('image_main')->isValid()) {
                $validated['image_main'] = $request->file('image_main')->store('produits/main', 'public');
                $validated['image_main'] = 'storage/' . $validated['image_main'];
                Log::info('Image principale uploadée', ['path' => $validated['image_main']]);
            }

            $produit = Produit::create($validated);
            Log::info('Produit créé', ['id' => $produit->id, 'name' => $produit->name]);

            if ($request->hasFile('secondary_images')) {
                foreach ($request->file('secondary_images') as $image) {
                    if ($image->isValid()) {
                        $path = $image->store('produits/secondary', 'public');
                        $imageRecord = Image::create([
                            'produit_id' => $produit->id,
                            'url' => 'storage/' . $path,
                            'reference' => 'image_' . time() . '_' . rand(1000, 9999),
                        ]);
                        Log::info('Image secondaire uploadée', ['image_id' => $imageRecord->id, 'path' => $path]);
                    }
                }
            }

            return redirect()->route('produits.index')->with('success', 'Produit créé avec succès!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur de validation:', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Erreur générale dans store:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()->with('error', 'Erreur lors de la création du produit.')->withInput();
        }
    }

    public function show($id)
    {
        $produit = Produit::with('images')->findOrFail($id);
        return view('back.pages.produits.show', compact('produit'));
    }

    public function edit($id)
    {
        $produit = Produit::with('images')->findOrFail($id);
        $sousCategories = SousCategorie::all();
        $colors = ['Noir', 'Blanc', 'Bleu', 'Rouge', 'Vert', 'Jaune', 'Gris'];
        return view('back.pages.produits.edit', compact('produit', 'sousCategories', 'colors'));
    }

    public function update(Request $request, $id)
    {
        Log::info('Début de la méthode update', ['produit_id' => $id]);

        try {
            $produit = Produit::findOrFail($id);

            $validated = $request->validate([
                'sous_categorie_id' => 'required|exists:sous_categories,id',
                'name' => 'required|string|max:255|unique:produits,name,' . $produit->id,
                'description' => 'nullable|string',
                'image_main' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'price' => 'required|numeric|min:0',
                'colors' => 'nullable|array|max:5',
                'colors.*' => 'string|in:Noir,Blanc,Bleu,Rouge,Vert,Jaune,Gris',
                'niveau_confort' => 'nullable|integer|between:1,5',
                'poids' => 'nullable|numeric|min:0',
                'secondary_images' => 'nullable|array|max:5',
                'secondary_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'delete_images' => 'nullable|array',
                'delete_images.*' => 'exists:images,id',
            ]);

            Log::info('Validation passée', ['input' => $request->except(['image_main', 'secondary_images', 'delete_images'])]);

            // Generate slug only if name has changed
            if ($validated['name'] !== $produit->name) {
                $slug = Str::slug($validated['name']) . '-' . Str::random(5);
                $count = Produit::where('slug', 'like', $slug . '%')->where('id', '!=', $produit->id)->count();
                $validated['slug'] = $count ? "{$slug}-{$count}" : $slug;
            } else {
                $validated['slug'] = $produit->slug;
            }

            // Normalize colors
            $validated['color'] = !empty($validated['colors'])
                ? implode(',', array_map('trim', array_map('strtolower', $validated['colors'])))
                : null;

            // Handle main image
            if ($request->hasFile('image_main') && $request->file('image_main')->isValid()) {
                if ($produit->image_main) {
                    $cheminRelatif = str_replace('storage/', '', $produit->image_main);
                    Storage::disk('public')->delete($cheminRelatif);
                    Log::info('Ancienne image principale supprimée', ['path' => $cheminRelatif]);
                }
                $validated['image_main'] = $request->file('image_main')->store('produits/main', 'public');
                $validated['image_main'] = 'storage/' . $validated['image_main'];
                Log::info('Nouvelle image principale uploadée', ['path' => $validated['image_main']]);
            } else {
                $validated['image_main'] = $produit->image_main;
            }

            // Handle deletion of secondary images
            if (!empty($request->input('delete_images'))) {
                foreach ($request->input('delete_images') as $imageId) {
                    $image = Image::where('id', $imageId)->where('produit_id', $produit->id)->first();
                    if ($image) {
                        $cheminRelatif = str_replace('storage/', '', $image->url);
                        Storage::disk('public')->delete($cheminRelatif);
                        $image->delete();
                        Log::info('Image secondaire supprimée', ['image_id' => $imageId, 'path' => $cheminRelatif]);
                    }
                }
            }

            // Handle new secondary images
            if ($request->hasFile('secondary_images')) {
                $currentImageCount = $produit->images()->count();
                $newImageCount = count($request->file('secondary_images'));
                if ($currentImageCount + $newImageCount > 5) {
                    return redirect()->back()->withErrors(['secondary_images' => 'Le nombre total d\'images secondaires ne peut pas dépasser 5.'])->withInput();
                }

                foreach ($request->file('secondary_images') as $image) {
                    if ($image->isValid()) {
                        $path = $image->store('produits/secondary', 'public');
                        $imageRecord = Image::create([
                            'produit_id' => $produit->id,
                            'url' => 'storage/' . $path,
                            'reference' => 'image_' . time() . '_' . rand(1000, 9999),
                        ]);
                        Log::info('Nouvelle image secondaire uploadée', ['image_id' => $imageRecord->id, 'path' => $path]);
                    }
                }
            }

            $produit->update($validated);
            Log::info('Produit mis à jour', ['id' => $produit->id, 'name' => $produit->name]);

            return redirect()->route('produits.index')->with('success', 'Produit mis à jour avec succès!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur de validation dans update:', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Erreur générale dans update:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour du produit.')->withInput();
        }
    }

    public function destroy($id)
    {
        Log::info('Début de la méthode destroy', ['produit_id' => $id]);

        try {
            $produit = Produit::findOrFail($id);

            if ($produit->image_main) {
                $cheminRelatif = str_replace('storage/', '', $produit->image_main);
                Storage::disk('public')->delete($cheminRelatif);
                Log::info('Image principale supprimée', ['path' => $cheminRelatif]);
            }

            foreach ($produit->images as $image) {
                $cheminRelatif = str_replace('storage/', '', $image->url);
                Storage::disk('public')->delete($cheminRelatif);
                $image->delete();
                Log::info('Image secondaire supprimée', ['image_id' => $image->id, 'path' => $cheminRelatif]);
            }

            $produit->delete();
            Log::info('Produit supprimé', ['id' => $id]);

            return redirect()->route('produits.index')->with('success', 'Produit supprimé avec succès!');

        } catch (\Exception $e) {
            Log::error('Erreur dans destroy:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()->with('error', 'Erreur lors de la suppression du produit.');
        }
    }
}