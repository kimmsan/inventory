<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // check if table is empty
        if (DB::table('users')->count() == 0) {
            DB::table('users')->insert([
                [
                    'name' => 'Super Admin',
                    'email' => 'superadmin@acculance.com',
                    'email_verified_at' => Carbon::now(),
                    'password' => Hash::make('acculance2022'),
                    'account_role' => 1,
                    'is_active' => 1,
                ],
            ]);
        }
    }
}