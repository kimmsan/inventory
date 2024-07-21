<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoUnitsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('units')->delete();

        \DB::table('units')->insert([
            0 => [
                'id' => 1,
                'name' => 'Piece',
                'slug' => 'piece',
                'code' => 'Pcs',
                'note' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            1 => [
                'id' => 2,
                'name' => '12 Pcs',
                'slug' => '12-pcs',
                'code' => 'Pack',
                'note' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}