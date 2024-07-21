<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoLoanAuthoritiesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('loan_authorities')->delete();

        \DB::table('loan_authorities')->insert([
            0 => [
                'id' => 1,
                'name' => 'Islami Bank Bangladesh Ltd',
                'slug' => 'islami-bank-bangladesh-ltd',
                'email' => 'ibbl@bank.com',
                'contact_number' => '017000000',
                'cc_limit' => '10000000.00',
                'address' => 'Dhaka, Bangladesh',
                'note' => '',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            1 => [
                'id' => 2,
                'name' => 'Dutch Bangla Bank',
                'slug' => 'dutch-bangla-bank',
                'email' => 'dbbl@bank.com',
                'contact_number' => '018000000',
                'cc_limit' => '10000000.00',
                'address' => 'Dhaka, Bangladesh',
                'note' => '',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}