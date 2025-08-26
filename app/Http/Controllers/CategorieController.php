<?php
namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategorieController extends Controller
{
    public function create()
    {
        return view('back.pages.categories.create');
    }

    public function index()
    {
        $categories = Categorie::latest()->get();
        return view('back.pages.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255|unique:categories,label',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Génération du slug si vide
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['label']);
        }

        // Gestion de l'image
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        Categorie::create([
            'label' => $validated['label'],
            'slug' => $validated['slug'],
            'image' => $validated['image'] ?? null,
        ]);

        return redirect()->route('categories.index')
                        ->with('success', 'Catégorie créée avec succès!');
    }

    public function show(Categorie $categorie)
    {
        //
    }

    public function edit(Categorie $categorie)
    {
        //
    }

    public function update(Request $request, Categorie $categorie)
    {
        //
    }

    public function destroy(Categorie $categorie)
    {
        //
    }
}