<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            // African source currencies
            ['code' => 'XOF', 'name' => 'West African CFA franc',      'symbol' => 'CFA', 'decimal_places' => 0, 'is_active' => true],
            ['code' => 'XAF', 'name' => 'Central African CFA franc',   'symbol' => 'CFA', 'decimal_places' => 0, 'is_active' => true],
            ['code' => 'GNF', 'name' => 'Guinean franc',                'symbol' => 'FG',  'decimal_places' => 0, 'is_active' => true],
            ['code' => 'GHS', 'name' => 'Ghanaian cedi',               'symbol' => 'GH₵', 'decimal_places' => 2, 'is_active' => true],
            ['code' => 'LRD', 'name' => 'Liberian dollar',             'symbol' => 'L$',  'decimal_places' => 2, 'is_active' => true],
            ['code' => 'SLE', 'name' => 'Sierra Leonean leone',        'symbol' => 'Le',  'decimal_places' => 2, 'is_active' => true],
            // Destination currency
            ['code' => 'CNY', 'name' => 'Chinese yuan renminbi',       'symbol' => '¥',   'decimal_places' => 2, 'is_active' => true],
            // Reference currencies
            ['code' => 'USD', 'name' => 'United States dollar',        'symbol' => '$',   'decimal_places' => 2, 'is_active' => true],
            ['code' => 'EUR', 'name' => 'Euro',                        'symbol' => '€',   'decimal_places' => 2, 'is_active' => true],
        ];

        foreach ($currencies as $currency) {
            DB::table('currencies')->updateOrInsert(
                ['code' => $currency['code']],
                array_merge($currency, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
