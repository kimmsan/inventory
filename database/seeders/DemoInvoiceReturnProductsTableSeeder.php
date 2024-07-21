<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoInvoiceReturnProductsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('invoice_return_products')->delete();

        \DB::table('invoice_return_products')->insert([
            0 => [
                'id' => 1,
                'sale_price' => 1045.0,
                'purchase_price' => 872.49,
                'quantity' => 1.0,
                'created_at' => now(),
                'updated_at' => now(),
                'return_id' => 1,
                'product_id' => 1,
            ],
            1 => [
                'id' => 2,
                'sale_price' => 1200.0,
                'purchase_price' => 960.6,
                'quantity' => 1.0,
                'created_at' => now(),
                'updated_at' => now(),
                'return_id' => 1,
                'product_id' => 2,
            ],
            2 => [
                'id' => 3,
                'sale_price' => 1500.0,
                'purchase_price' => 1260.6,
                'quantity' => 1.0,
                'created_at' => now(),
                'updated_at' => now(),
                'return_id' => 1,
                'product_id' => 3,
            ],
            3 => [
                'id' => 4,
                'sale_price' => 1045.0,
                'purchase_price' => 877.85,
                'quantity' => 3.0,
                'created_at' => now(),
                'updated_at' => now(),
                'return_id' => 2,
                'product_id' => 1,
            ],
            4 => [
                'id' => 5,
                'sale_price' => 1200.0,
                'purchase_price' => 953.03,
                'quantity' => 3.0,
                'created_at' => now(),
                'updated_at' => now(),
                'return_id' => 2,
                'product_id' => 2,
            ],
            5 => [
                'id' => 6,
                'sale_price' => 1500.0,
                'purchase_price' => 1145.89,
                'quantity' => 3.0,
                'created_at' => now(),
                'updated_at' => now(),
                'return_id' => 2,
                'product_id' => 3,
            ],
        ]);
    }
}