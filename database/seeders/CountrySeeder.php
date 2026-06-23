<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $xof = Currency::where('code', 'XOF')->value('id');
        $xaf = Currency::where('code', 'XAF')->value('id');
        $gnf = Currency::where('code', 'GNF')->value('id');
        $ghs = Currency::where('code', 'GHS')->value('id');
        $lrd = Currency::where('code', 'LRD')->value('id');
        $sle = Currency::where('code', 'SLE')->value('id');
        $cny = Currency::where('code', 'CNY')->value('id');

        $countries = [
            // Source countries (Africa)
            [
                'name' => "Côte d'Ivoire", 'iso_code' => 'CI', 'phone_prefix' => '+225',
                'currency_id' => $xof, 'is_source' => true, 'is_destination' => false,
                'flag_url' => 'https://flagcdn.com/w80/ci.png',
            ],
            [
                'name' => 'Sénégal', 'iso_code' => 'SN', 'phone_prefix' => '+221',
                'currency_id' => $xof, 'is_source' => true, 'is_destination' => false,
                'flag_url' => 'https://flagcdn.com/w80/sn.png',
            ],
            [
                'name' => 'Guinée-Bissau', 'iso_code' => 'GW', 'phone_prefix' => '+245',
                'currency_id' => $xof, 'is_source' => true, 'is_destination' => false,
                'flag_url' => 'https://flagcdn.com/w80/gw.png',
            ],
            [
                'name' => 'Gabon', 'iso_code' => 'GA', 'phone_prefix' => '+241',
                'currency_id' => $xaf, 'is_source' => true, 'is_destination' => false,
                'flag_url' => 'https://flagcdn.com/w80/ga.png',
            ],
            [
                'name' => 'Guinée', 'iso_code' => 'GN', 'phone_prefix' => '+224',
                'currency_id' => $gnf, 'is_source' => true, 'is_destination' => false,
                'flag_url' => 'https://flagcdn.com/w80/gn.png',
            ],
            [
                'name' => 'Ghana', 'iso_code' => 'GH', 'phone_prefix' => '+233',
                'currency_id' => $ghs, 'is_source' => true, 'is_destination' => false,
                'flag_url' => 'https://flagcdn.com/w80/gh.png',
            ],
            [
                'name' => 'Liberia', 'iso_code' => 'LR', 'phone_prefix' => '+231',
                'currency_id' => $lrd, 'is_source' => true, 'is_destination' => false,
                'flag_url' => 'https://flagcdn.com/w80/lr.png',
            ],
            [
                'name' => 'Sierra Leone', 'iso_code' => 'SL', 'phone_prefix' => '+232',
                'currency_id' => $sle, 'is_source' => true, 'is_destination' => false,
                'flag_url' => 'https://flagcdn.com/w80/sl.png',
            ],
            // Destination country (China)
            [
                'name' => 'China', 'iso_code' => 'CN', 'phone_prefix' => '+86',
                'currency_id' => $cny, 'is_source' => false, 'is_destination' => true,
                'flag_url' => 'https://flagcdn.com/w80/cn.png',
            ],
        ];

        foreach ($countries as $country) {
            DB::table('countries')->updateOrInsert(
                ['iso_code' => $country['iso_code']],
                array_merge($country, [
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
