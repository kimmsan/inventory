<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // check if table is empty
        if (DB::table('user_role')->count() == 0) {
            DB::table('user_role')->insert([
                [
                    'user_id' => 1,
                    'role_id' => 2,
                ],
            ]);
        }
    }
}
