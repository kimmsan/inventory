<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoQuotationProductsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('quotation_products')->delete();

        \DB::table('quotation_products')->insert([
            0 => [
                'id' => 1,
                'quantity' => 5.0,
                'purchase_price' => 872.49,
                'sale_price' => 950.0,
                'unit_cost' => 1045.0,
                'tax_amount' => 95.0,
                'created_at' => now(),
                'updated_at' => now(),
                'quotation_id' => 1,
                'product_id' => 1,
            ],
            1 => [
                'id' => 2,
                'quantity' => 5.0,
                'purchase_price' => 960.6,
                'sale_price' => 1200.0,
                'unit_cost' => 1200.0,
                'tax_amount' => 109.09,
                'created_at' => now(),
                'updated_at' => now(),
                'quotation_id' => 1,
                'product_id' => 2,
            ],
            2 => [
                'id' => 3,
                'quantity' => 5.0,
                'purchase_price' => 1260.6,
                'sale_price' => 1500.0,
                'unit_cost' => 1500.0,
                'tax_amount' => 0.0,
                'created_at' => now(),
                'updated_at' => now(),
                'quotation_id' => 1,
                'product_id' => 3,
            ],
        ]);
    }
}