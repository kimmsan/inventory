<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoSalaryIncrementsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('salary_increments')->delete();

        \DB::table('salary_increments')->insert([
            0 => [
                'id' => 1,
                'reason' => 'Good performance',
                'slug' => 'good-performance',
                'increment_amount' => 1500.0,
                'increment_date' => now(),
                'note' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'empolyee_id' => 1,
                'created_by' => 1,
            ],
            1 => [
                'id' => 2,
                'reason' => 'Good performance',
                'slug' => 'good-performance-2',
                'increment_amount' => 1000.0,
                'increment_date' => now(),
                'note' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'empolyee_id' => 2,
                'created_by' => 1,
            ],
        ]);
    }
}