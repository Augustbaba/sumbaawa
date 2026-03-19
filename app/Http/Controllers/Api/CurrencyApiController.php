<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurrencyApiController extends Controller
{
    /**
     * GET /api/v1/currencies
     * Retourne toutes les devises actives avec leur taux de change.
     * Route publique — utilisable même sans être connecté.
     */
    public function index(): JsonResponse
    {
        $currencies = Currency::where('is_active', true)
            ->orderBy('is_default', 'desc')
            ->orderBy('code')
            ->get()
            ->map(fn ($c) => $this->formatCurrency($c));

        return response()->json([
            'success'    => true,
            'currencies' => $currencies,
        ]);
    }

    /**
     * PATCH /api/v1/user/currency
     * Met à jour la devise préférée de l'utilisateur connecté.
     * Route protégée (auth:sanctum).
     */
    public function updatePreferred(Request $request): JsonResponse
    {
        $request->validate([
            'currency_id' => ['required', 'integer', 'exists:currencies,id'],
        ]);

        $currency = Currency::where('id', $request->currency_id)
            ->where('is_active', true)
            ->firstOrFail();

        $request->user()->update([
            'preferred_currency_id' => $currency->id,
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Devise mise à jour.',
            'currency' => $this->formatCurrency($currency),
        ]);
    }

    /**
     * Format uniforme d'une devise pour le mobile.
     */
    private function formatCurrency(Currency $c): array
    {
        return [
            'id'            => $c->id,
            'code'          => $c->code,
            'symbol'        => $c->symbol,
            'name'          => $c->name,
            'exchange_rate' => (float) $c->exchange_rate, // 1 USD = X XOF → taux XOF/devise
            'is_default'    => (bool) $c->is_default,
        ];
    }
}
