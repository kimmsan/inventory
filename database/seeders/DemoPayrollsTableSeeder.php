<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoPayrollsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('payrolls')->delete();

        \DB::table('payrolls')->insert([
            0 => [
                'id' => 1,
                'slug' => '626d8d65958b3',
                'salary_month' => 'February',
                'deduction_reason' => null,
                'deduction_amount' => null,
                'mobile_bill' => 100.0,
                'food_bill' => 100.0,
                'bonus' => 100.0,
                'commission' => 100.0,
                'advance' => 100.0,
                'festival_bonus' => 100.0,
                'travel_allowance' => 100.0,
                'others' => 100.0,
                'salary_date' => today(),
                'note' => 'This is a note',
                'status' => 1,
                'image_path' => '1651346789.png',
                'created_at' => now(),
                'updated_at' => now(),
                'employee_id' => 2,
                'transaction_id' => 22,
                'created_by' => 1,
            ],
            1 => [
                'id' => 2,
                'slug' => '626d8d819b8b9',
                'salary_month' => 'February',
                'deduction_reason' => null,
                'deduction_amount' => null,
                'mobile_bill' => 100.0,
                'food_bill' => 100.0,
                'bonus' => 100.0,
                'commission' => 100.0,
                'advance' => 100.0,
                'festival_bonus' => 100.0,
                'travel_allowance' => 100.0,
                'others' => 100.0,
                'salary_date' => today(),
                'note' => null,
                'status' => 1,
                'image_path' => '',
                'created_at' => now(),
                'updated_at' => now(),
                'employee_id' => 1,
                'transaction_id' => 23,
                'created_by' => 1,
            ],
        ]);
    }
}