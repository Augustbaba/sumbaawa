<?php

namespace App\Helpers;

use App\Models\Currency;

class CurrencyHelper
{
    public static function convert($amount, $toCurrencyCode = null)
    {
        $currentCurrency = config('app.current_currency');

        if (!$toCurrencyCode) {
            $toCurrencyCode = $currentCurrency->code;
        }

        if ($toCurrencyCode === 'XOF') {
            // XOF est la devise de base
            return $amount;
        }

        $targetCurrency = Currency::where('code', $toCurrencyCode)->first();

        if (!$targetCurrency) {
            return $amount;
        }

        // Conversion : montant / taux
        return $amount / $targetCurrency->exchange_rate;
    }

    public static function format($amount, $currencyCode = null)
    {
        $currentCurrency = config('app.current_currency');

        if (!$currencyCode) {
            $currencyCode = $currentCurrency->code;
        }

        $currency = Currency::where('code', $currencyCode)->first();

        if (!$currency) {
            return number_format($amount, 0, ',', ' ');
        }

        $convertedAmount = self::convert($amount, $currencyCode);

        // Format selon la devise
        $formatted = number_format($convertedAmount, 0, ',', ' ');

        return $formatted . ' ' . $currency->symbol;
    }
}
