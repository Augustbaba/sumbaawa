<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class RechargeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  /**
 * Affiche le formulaire de création d'une nouvelle recharge
 */
public function create()
{
    // Retourne uniquement la vue du formulaire de création
    return view('back.pages.recharges.create');
}

    // Les autres méthodes peuvent rester vides pour l'instant
    public function index() {}
    public function store(Request $request) {}
    public function show(Recharge $recharge) {}
    public function edit(Recharge $recharge) {}
    public function update(Request $request, Recharge $recharge) {}
    public function destroy(Recharge $recharge) {}
}