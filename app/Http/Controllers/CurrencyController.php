<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currencies = Currency::orderBy('name')->paginate(20);

        return view('back.pages.currencies.index', compact('currencies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('back.pages.currencies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => [
                'required',
                'string',
                'max:3',
                'unique:currencies,code',
                'regex:/^[A-Z]{3}$/'
            ],
            'symbol' => 'required|string|max:10',
            'name' => 'required|string|max:100',
            'exchange_rate' => 'required|numeric|min:0.00000001',
            'is_active' => 'boolean',
            'is_default' => 'boolean'
        ]);

        DB::beginTransaction();
        try {
            // Si c'est la devise par défaut, désactiver les autres
            // if ($request->is_default) {
            //     Currency::where('is_default', true)->update(['is_default' => false]);
            // }

            // Si le taux est 1, c'est probablement la devise de base
            // if ($request->exchange_rate == 1 && !$request->is_default) {
            //     return back()->with('warning', 'Une devise avec un taux de 1 doit être la devise par défaut.');
            // }

            $currency = Currency::create([
                'code' => strtoupper($request->code),
                'symbol' => $request->symbol,
                'name' => $request->name,
                'exchange_rate' => $request->exchange_rate,
                'is_active' => $request->is_active ?? true,
                // 'is_default' => $request->is_default ?? false
            ]);

            DB::commit();

            return redirect()->route('admin.currencies.index')
                ->with('success', 'Devise créée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la création de la devise: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Currency $currency)
    {
        $userCount = \App\Models\User::where('preferred_currency_id', $currency->id)->count();

        return view('back.pages.currencies.show', compact('currency', 'userCount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Currency $currency)
    {
        return view('back.pages.currencies.edit', compact('currency'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Currency $currency)
    {
        $request->validate([
            'code' => [
                'required',
                'string',
                'max:3',
                Rule::unique('currencies', 'code')->ignore($currency->id),
                'regex:/^[A-Z]{3}$/'
            ],
            'symbol' => 'required|string|max:10',
            'name' => 'required|string|max:100',
            'exchange_rate' => 'required|numeric|min:0.00000001',
            'is_active' => 'boolean',
            'is_default' => 'boolean'
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'code' => strtoupper($request->code),
                'symbol' => $request->symbol,
                'name' => $request->name,
                'exchange_rate' => $request->exchange_rate,
                'is_active' => $request->is_active ?? true,
            ];

            // Gestion de la devise par défaut
            // if ($request->is_default && !$currency->is_default) {
            //     // Désactiver l'ancienne devise par défaut
            //     Currency::where('is_default', true)->update(['is_default' => false]);
            //     $data['is_default'] = true;
            //     $data['rate'] = 1; // La devise par défaut doit avoir un taux de 1
            // } elseif (!$request->is_default && $currency->is_default) {
            //     // Empêcher de désactiver la dernière devise par défaut
            //     $defaultCount = Currency::where('is_default', true)->count();
            //     if ($defaultCount <= 1) {
            //         return back()->with('error', 'Vous devez avoir au moins une devise par défaut.');
            //     }
            //     $data['is_default'] = false;
            // } else {
            //     $data['is_default'] = $currency->is_default;
            // }

            $currency->update($data);

            DB::commit();

            return redirect()->route('admin.currencies.index')
                ->with('success', 'Devise mise à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Currency $currency)
    {
        // Vérifier les contraintes
        // if ($currency->is_default) {
        //     return back()->with('error', 'Impossible de supprimer la devise par défaut.');
        // }

        // $userCount = \App\Models\User::where('currency_code', $currency->code)->count();
        // if ($userCount > 0) {
        //     return back()->with('error', "Cette devise est utilisée par $userCount utilisateur(s). Réassignez-les avant de supprimer.");
        // }

        // DB::beginTransaction();
        // try {
        //     $currency->delete();
        //     DB::commit();

        //     return redirect()->route('admin.currencies.index')
        //         ->with('success', 'Devise supprimée avec succès.');

        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        // }
    }

    /**
     * Toggle l'état actif/inactif d'une devise
     */
    public function toggleActive(Request $request, Currency $currency)
    {
        if ($currency->is_default) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de désactiver la devise par défaut'
            ]);
        }

        $currency->update(['is_active' => !$currency->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour',
            'is_active' => $currency->is_active
        ]);
    }

    /**
     * Définir comme devise par défaut
     */
    public function setAsDefault(Currency $currency)
    {
        // DB::beginTransaction();
        // try {
        //     // Désactiver l'ancienne devise par défaut
        //     Currency::where('is_default', true)->update(['is_default' => false]);

        //     // Définir la nouvelle comme par défaut
        //     $currency->update([
        //         'is_default' => true,
        //         'exchange_rate' => 1, // La devise par défaut a toujours un taux de 1
        //         'is_active' => true // S'assurer qu'elle est active
        //     ]);

        //     DB::commit();

        //     return redirect()->route('admin.currencies.index')
        //         ->with('success', 'Devise par défaut mise à jour.');

        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return back()->with('error', 'Erreur: ' . $e->getMessage());
        // }
    }

    /**
     * Mettre à jour les positions via drag & drop
     */
    public function updatePositions(Request $request)
    {
        // $request->validate([
        //     'positions' => 'required|array',
        //     'positions.*.id' => 'required|exists:currencies,id',
        //     'positions.*.position' => 'required|integer|min:0'
        // ]);

        // DB::beginTransaction();
        // try {
        //     foreach ($request->positions as $item) {
        //         Currency::where('id', $item['id'])->update(['position' => $item['position']]);
        //     }

        //     DB::commit();

        //     return response()->json(['success' => true, 'message' => 'Positions mises à jour']);

        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        // }
    }

    /**
     * Mettre à jour les taux depuis une API
     */
    public function updateRates(Request $request)
    {
        // try {
        //     $apiKey = config('services.exchange_rate.api_key');
        //     $baseCurrency = Currency::where('is_default', true)->first();

        //     if (!$baseCurrency) {
        //         return back()->with('error', 'Aucune devise par défaut trouvée.');
        //     }

        //     // Utiliser une API de taux de change
        //     $response = \Illuminate\Support\Facades\Http::get("https://api.exchangerate-api.com/v4/latest/{$baseCurrency->code}");

        //     if ($response->successful()) {
        //         $rates = $response->json()['rates'];

        //         $updatedCount = 0;
        //         $currencies = Currency::where('code', '!=', $baseCurrency->code)->get();

        //         foreach ($currencies as $currency) {
        //             if (isset($rates[$currency->code])) {
        //                 $currency->update(['exchange_rate' => $rates[$currency->code]]);
        //                 $updatedCount++;
        //             }
        //         }

        //         return back()->with('success', "Taux de change mis à jour pour $updatedCount devise(s).");
        //     } else {
        //         return back()->with('error', 'Impossible de récupérer les taux de change.');
        //     }
        // } catch (\Exception $e) {
        //     return back()->with('error', 'Erreur: ' . $e->getMessage());
        // }
    }

    /**
     * Afficher le formulaire de mise à jour manuelle des taux
     */
    public function showUpdateRatesForm()
    {
        $currencies = Currency::where('is_default', false)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('back.pages.currencies.update-rates', compact('currencies'));
    }

    /**
     * Mettre à jour manuellement les taux
     */
    public function updateRatesManual(Request $request)
    {
        // $request->validate([
        //     'exchange_rates' => 'required|array',
        //     'exchange_rates.*' => 'required|numeric|min:0.00000001'
        // ]);

        // DB::beginTransaction();
        // try {
        //     $updatedCount = 0;
        //     foreach ($request->exchange_rates as $currencyId => $exchange_rate) {
        //         $currency = Currency::find($currencyId);
        //         if ($currency && !$currency->is_default) {
        //             $currency->update(['exchange_rate' => $exchange_rate]);
        //             $updatedCount++;
        //         }
        //     }

        //     DB::commit();

        //     return redirect()->route('admin.currencies.index')
        //         ->with('success', "$updatedCount taux de change mis à jour.");

        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return back()->with('error', 'Erreur: ' . $e->getMessage());
        // }
    }
}
