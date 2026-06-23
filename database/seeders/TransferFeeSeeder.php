<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransferFeeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('transfer_fees')->truncate();

        $fees = [
            // ── Africa → China ────────────────────────────────────────────────
            ['from_currency' => 'XOF', 'to_currency' => 'CNY', 'min_amount' => 5000,   'max_amount' => 500000,  'fee_type' => 'mixed', 'fixed_fee' => 500,  'percentage_fee' => 1.50, 'min_fee' => 500,  'max_fee' => 15000],
            ['from_currency' => 'XOF', 'to_currency' => 'CNY', 'min_amount' => 500001, 'max_amount' => 2000000, 'fee_type' => 'mixed', 'fixed_fee' => 500,  'percentage_fee' => 1.20, 'min_fee' => 500,  'max_fee' => 50000],
            ['from_currency' => 'XAF', 'to_currency' => 'CNY', 'min_amount' => 5000,   'max_amount' => 500000,  'fee_type' => 'mixed', 'fixed_fee' => 500,  'percentage_fee' => 1.50, 'min_fee' => 500,  'max_fee' => 15000],
            ['from_currency' => 'XAF', 'to_currency' => 'CNY', 'min_amount' => 500001, 'max_amount' => 2000000, 'fee_type' => 'mixed', 'fixed_fee' => 500,  'percentage_fee' => 1.20, 'min_fee' => 500,  'max_fee' => 50000],
            ['from_currency' => 'GHS', 'to_currency' => 'CNY', 'min_amount' => 10,     'max_amount' => 2000,    'fee_type' => 'mixed', 'fixed_fee' => 2,    'percentage_fee' => 1.50, 'min_fee' => 2,    'max_fee' => 60],
            ['from_currency' => 'GHS', 'to_currency' => 'CNY', 'min_amount' => 2001,   'max_amount' => 10000,   'fee_type' => 'mixed', 'fixed_fee' => 2,    'percentage_fee' => 1.20, 'min_fee' => 2,    'max_fee' => 200],
            ['from_currency' => 'GNF', 'to_currency' => 'CNY', 'min_amount' => 50000,  'max_amount' => 5000000, 'fee_type' => 'mixed', 'fixed_fee' => 5000, 'percentage_fee' => 1.50, 'min_fee' => 5000, 'max_fee' => 150000],
            ['from_currency' => 'LRD', 'to_currency' => 'CNY', 'min_amount' => 10,     'max_amount' => 2000,    'fee_type' => 'mixed', 'fixed_fee' => 1,    'percentage_fee' => 1.50, 'min_fee' => 1,    'max_fee' => 60],
            ['from_currency' => 'SLE', 'to_currency' => 'CNY', 'min_amount' => 10,     'max_amount' => 5000,    'fee_type' => 'mixed', 'fixed_fee' => 1,    'percentage_fee' => 1.50, 'min_fee' => 1,    'max_fee' => 150],

            // ── China → Africa ────────────────────────────────────────────────
            ['from_currency' => 'CNY', 'to_currency' => 'XOF', 'min_amount' => 50,    'max_amount' => 5000,    'fee_type' => 'mixed', 'fixed_fee' => 5,    'percentage_fee' => 1.50, 'min_fee' => 5,    'max_fee' => 150],
            ['from_currency' => 'CNY', 'to_currency' => 'XOF', 'min_amount' => 5001,  'max_amount' => 20000,   'fee_type' => 'mixed', 'fixed_fee' => 5,    'percentage_fee' => 1.20, 'min_fee' => 5,    'max_fee' => 500],
            ['from_currency' => 'CNY', 'to_currency' => 'XAF', 'min_amount' => 50,    'max_amount' => 5000,    'fee_type' => 'mixed', 'fixed_fee' => 5,    'percentage_fee' => 1.50, 'min_fee' => 5,    'max_fee' => 150],
            ['from_currency' => 'CNY', 'to_currency' => 'XAF', 'min_amount' => 5001,  'max_amount' => 20000,   'fee_type' => 'mixed', 'fixed_fee' => 5,    'percentage_fee' => 1.20, 'min_fee' => 5,    'max_fee' => 500],
            ['from_currency' => 'CNY', 'to_currency' => 'GHS', 'min_amount' => 50,    'max_amount' => 5000,    'fee_type' => 'mixed', 'fixed_fee' => 5,    'percentage_fee' => 1.50, 'min_fee' => 5,    'max_fee' => 150],
            ['from_currency' => 'CNY', 'to_currency' => 'GNF', 'min_amount' => 50,    'max_amount' => 5000,    'fee_type' => 'mixed', 'fixed_fee' => 5,    'percentage_fee' => 1.50, 'min_fee' => 5,    'max_fee' => 150],
            ['from_currency' => 'CNY', 'to_currency' => 'LRD', 'min_amount' => 50,    'max_amount' => 5000,    'fee_type' => 'mixed', 'fixed_fee' => 5,    'percentage_fee' => 1.50, 'min_fee' => 5,    'max_fee' => 150],
            ['from_currency' => 'CNY', 'to_currency' => 'SLE', 'min_amount' => 50,    'max_amount' => 5000,    'fee_type' => 'mixed', 'fixed_fee' => 5,    'percentage_fee' => 1.50, 'min_fee' => 5,    'max_fee' => 150],
        ];

        foreach ($fees as $fee) {
            DB::table('transfer_fees')->insert(array_merge($fee, [
                'from_country_id' => null,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
