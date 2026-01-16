<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    public function run()
    {
        $currencies = [
            [
                'code' => 'XOF',
                'symbol' => 'FCFA',
                'name' => 'Franc CFA',
                'exchange_rate' => 1.0000, // Devise de base
                'is_default' => true,
                'is_active' => true,
            ],
            [
                'code' => 'EUR',
                'symbol' => '€',
                'name' => 'Euro',
                'exchange_rate' => 655.957, // 1 EUR = 655.957 XOF
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'code' => 'USD',
                'symbol' => '$',
                'name' => 'Dollar américain',
                'exchange_rate' => 600.0000, // À ajuster selon le taux réel
                'is_default' => false,
                'is_active' => true,
            ],
        ];

        foreach ($currencies as $currency) {
            Currency::updateOrCreate(
                ['code' => $currency['code']],
                $currency
            );
        }
    }
}
