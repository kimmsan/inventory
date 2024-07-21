<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoAssetTypesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('asset_types')->delete();

        \DB::table('asset_types')->insert([
            0 => [
                'id' => 1,
                'name' => 'Furniture',
                'slug' => 'furniture',
                'note' => '',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            1 => [
                'id' => 2,
                'name' => 'Advance Payment',
                'slug' => 'advance-payment',
                'note' => '',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}