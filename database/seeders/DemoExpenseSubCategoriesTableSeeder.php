<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoExpenseSubCategoriesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('expense_sub_categories')->delete();

        \DB::table('expense_sub_categories')->insert([
            0 => [
                'id' => 1,
                'name' => 'Office Rent',
                'code' => 1,
                'slug' => 'office-rent',
                'note' => '',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'exp_id' => 1,
            ],
            1 => [
                'id' => 2,
                'name' => 'Office Stationary',
                'code' => 2,
                'slug' => 'office-stationary',
                'note' => '',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'exp_id' => 2,
            ],
        ]);
    }
}