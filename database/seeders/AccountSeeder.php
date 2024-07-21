<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // check if table is empty
        if (DB::table('accounts')->count() == 0) {
            DB::table('accounts')->insert([
                [
                    'bank_name' => 'Cash',
                    'branch_name' => 'Office',
                    'account_number' => 'CASH-0001',
                    'slug' => 'cash-0001',
                    'created_by' => '1',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'date' => now(),
                    'note' => null,
                ],
            ]);
        }
    }
}