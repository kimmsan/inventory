<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoPurchaseReturnProductsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('purchase_return_products')->delete();

        \DB::table('purchase_return_products')->insert([
            0 => [
                'id' => 6,
                'purchase_price' => 880.0,
                'quantity' => 2.0,
                'created_at' => now(),
                'updated_at' => now(),
                'return_id' => 1,
                'product_id' => 1,
            ],
            1 => [
                'id' => 5,
                'purchase_price' => 900.0,
                'quantity' => 2.0,
                'created_at' => now(),
                'updated_at' => now(),
                'return_id' => 1,
                'product_id' => 2,
            ],
            2 => [
                'id' => 4,
                'purchase_price' => 1200.0,
                'quantity' => 2.0,
                'created_at' => now(),
                'updated_at' => now(),
                'return_id' => 1,
                'product_id' => 3,
            ],
            3 => [
                'id' => 7,
                'purchase_price' => 880.0,
                'quantity' => 5.0,
                'created_at' => now(),
                'updated_at' => now(),
                'return_id' => 2,
                'product_id' => 1,
            ],
            4 => [
                'id' => 8,
                'purchase_price' => 900.0,
                'quantity' => 5.0,
                'created_at' => now(),
                'updated_at' => now(),
                'return_id' => 2,
                'product_id' => 2,
            ],
            5 => [
                'id' => 9,
                'purchase_price' => 1200.0,
                'quantity' => 5.0,
                'created_at' => now(),
                'updated_at' => now(),
                'return_id' => 2,
                'product_id' => 3,
            ],
        ]);
    }
}