<?php

namespace App\Http\Middleware;

use App\Helpers\FrontHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddCurrencyToResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Si c'est une réponse JSON, ajouter les infos de devise
        if ($request->expectsJson() || $request->isJson()) {
            $content = $response->getOriginalContent();

            if (is_array($content)) {
                $currency = FrontHelper::current_currency();
                $content['currency_info'] = [
                    'code' => $currency->code,
                    'symbol' => $currency->symbol,
                    'exchange_rate' => $currency->exchange_rate
                ];

                $response->setContent(json_encode($content));
            }
        }

        return $response;
    }
}
