<?php

namespace App\Http\Controllers;

use App\Models\Pays;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaysController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pays = Pays::orderBy('name')->get();
        return view('back.pages.pays.index', compact('pays'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('back.pages.pays.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:pays,name',
        ]);

        Pays::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return redirect()->route('pays.index')
            ->with('success', 'Pays ajouté avec succès');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pays $pay)
    {
        return view('back.pages.pays.edit', compact('pay'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pays $pay)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:pays,name,' . $pay->id,
        ]);

        $pay->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return redirect()->route('pays.index')
            ->with('success', 'Pays modifié avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pays $pay)
    {
        $pay->delete();

        return redirect()->route('pays.index')
            ->with('success', 'Pays supprimé avec succès');
    }
}
