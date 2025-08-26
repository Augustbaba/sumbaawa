<?php
namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\SousCategorie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SousCategorieController extends Controller
{
    public function index()
    {
        $sousCategories = SousCategorie::with('categorie')->latest()->paginate(10);
        return view('back.pages.sous_categories.index', compact('sousCategories'));
    }

    public function create()
    {
        $categories = Categorie::all();
        return view('back.pages.sous_categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'categorie_id' => 'required|exists:categories,id',
            'label' => 'required|string|max:255|unique:sous_categories,label',
        ]);

        // Auto-generate slug
        $validated['slug'] = Str::slug($validated['label']);

        SousCategorie::create($validated);

        return redirect()->route('sous-categories.index')
                         ->with('success', 'Sous-catégorie créée avec succès!');
    }

    public function show(SousCategorie $sousCategorie)
    {
        return view('back.pages.sous_categories.show', compact('sousCategorie'));
    }
public function edit($id)
{
    $sousCategorie = SousCategorie::findOrFail($id);
    $categories = Categorie::all();
    return view('back.pages.sous_categories.edit', compact('sousCategorie', 'categories'));
}
    public function update(Request $request, SousCategorie $sousCategorie)
    {
        $validated = $request->validate([
            'categorie_id' => 'required|exists:categories,id',
            'label' => 'required|string|max:255|unique:sous_categories,label,'.$sousCategorie->id,
        ]);

        // Auto-generate slug
        $validated['slug'] = Str::slug($validated['label']);

        $sousCategorie->update($validated);

        return redirect()->route('sous-categories.index')
                         ->with('success', 'Sous-catégorie mise à jour avec succès!');
    }

    public function destroy(SousCategorie $sousCategorie)
    {
        $sousCategorie->delete();

        return redirect()->route('sous-categories.index')
                         ->with('success', 'Sous-catégorie supprimée avec succès!');
    }
}