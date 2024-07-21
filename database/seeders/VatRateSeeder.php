<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VatRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // check if table is empty
        if (DB::table('vat_rates')->count() == 0) {
            DB::table('vat_rates')->insert([
                [
                    'name' => 'VAT 0%',
                    'slug' => 'vat-0',
                    'code' => 'VAT@0',
                    'rate' => '0.00',
                ],
            ]);
        }
    }
}
