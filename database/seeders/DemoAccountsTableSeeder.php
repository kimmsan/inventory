<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoAccountsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('accounts')->delete();

        \DB::table('accounts')->insert([
            0 => [
                'id' => 1,
                'bank_name' => 'Cash',
                'branch_name' => 'Office',
                'account_number' => 'CASH-0001',
                'slug' => 'cash-0001',
                'date' => now(),
                'note' => '',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
            ],
            1 => [
                'id' => 2,
                'bank_name' => 'Dutch Bangla Bank',
                'branch_name' => 'Mirpur',
                'account_number' => 'DBBL-0003',
                'slug' => 'dbbl-0002',
                'date' => now(),
                'note' => '',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
            ],
            2 => [
                'id' => 3,
                'bank_name' => 'Islami Bank Bangladesh Ltd',
                'branch_name' => 'Mirpur',
                'account_number' => 'IBBL-0002',
                'slug' => 'ibbl-0003',
                'date' => now(),
                'note' => '',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
            ],
        ]);
    }
}