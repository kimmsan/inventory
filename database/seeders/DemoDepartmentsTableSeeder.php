<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoDepartmentsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('departments')->delete();

        \DB::table('departments')->insert([
            0 => [
                'id' => 1,
                'name' => 'Marketing',
                'slug' => 'marketing',
                'note' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            1 => [
                'id' => 2,
                'name' => 'Sales',
                'slug' => 'sales',
                'note' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}