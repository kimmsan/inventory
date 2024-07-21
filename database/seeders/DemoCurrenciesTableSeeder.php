<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoCurrenciesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('currencies')->delete();

        \DB::table('currencies')->insert([
            0 => [
                'id' => 1,
                'name' => 'United States Dollar',
                'slug' => 'united-states-dollar',
                'code' => 'US Dollar',
                'symbol' => '$',
                'position' => 'left',
                'note' => 'This is default currency',
                'status' => 1,
            ],
        ]);
    }
}