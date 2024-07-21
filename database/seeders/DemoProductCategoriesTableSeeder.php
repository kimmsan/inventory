<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoProductCategoriesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('product_categories')->delete();

        \DB::table('product_categories')->insert([
            0 => [
                'id' => 1,
                'name' => 'Electronics',
                'slug' => 'electronics',
                'code' => 1,
                'note' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            1 => [
                'id' => 2,
                'name' => 'Accessories',
                'slug' => 'accessories',
                'code' => 2,
                'note' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}