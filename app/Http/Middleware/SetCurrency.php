<?php

namespace App\Http\Middleware;

use App\Models\Currency;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCurrency
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $currency = $this->getCurrentCurrency($request);

        // Stocker dans la config pour l'utiliser partout
        config(['app.current_currency' => $currency]);
        view()->share('currentCurrency', $currency);

        return $next($request);
    }

    private function getCurrentCurrency(Request $request)
    {
        // 1. Vérifier si l'utilisateur est connecté et a une préférence
        if (auth()->check() && auth()->user()->preferred_currency_id) {
            $currency = Currency::find(auth()->user()->preferred_currency_id);
            if ($currency) {
                // Synchroniser avec le cookie
                cookie()->queue('currency_code', $currency->code, 525600); // 1 an
                return $currency;
            }
        }

        // 2. Vérifier le cookie
        if ($request->hasCookie('currency_code')) {
            $currency = Currency::where('code', $request->cookie('currency_code'))
                               ->where('is_active', true)
                               ->first();
            if ($currency) {
                return $currency;
            }
        }

        // 3. Devise par défaut
        return Currency::where('is_default', true)->first()
               ?? Currency::where('code', 'XOF')->first();
    }
}
