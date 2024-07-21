<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoAssetsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('assets')->delete();

        \DB::table('assets')->insert([
            0 => [
                'id' => 1,
                'name' => 'Work Station',
                'slug' => 'work-station',
                'asset_cost' => 50000.0,
                'depreciation' => 0,
                'depreciation_type' => null,
                'salvage_value' => null,
                'useful_life' => null,
                'daily_depreciation' => null,
                'note' => 'This is a note',
                'image_path' => 'workstation.jpeg',
                'date' => now(),
                'expire_date' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'cat_id' => 1,
                'created_by' => 1,
            ],
            1 => [
                'id' => 2,
                'name' => 'Office Rent Advance',
                'slug' => 'office-rent-advance',
                'asset_cost' => 200000.0,
                'depreciation' => 1,
                'depreciation_type' => 0,
                'salvage_value' => 0.0,
                'useful_life' => 36.0,
                'daily_depreciation' => 182.48,
                'note' => 'This is a note!',
                'image_path' => '',
                'date' => today(),
                'expire_date' => today()->subYears(3),
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'cat_id' => 2,
                'created_by' => 1,
            ],
        ]);
    }
}