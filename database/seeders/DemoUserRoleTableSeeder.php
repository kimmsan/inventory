<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoUserRoleTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('user_role')->delete();

        \DB::table('user_role')->insert([
            0 => [
                'user_id' => 1,
                'role_id' => 2,
            ],
            1 => [
                'user_id' => 2,
                'role_id' => 3,
            ],
            2 => [
                'user_id' => 3,
                'role_id' => 4,
            ],
            3 => [
                'user_id' => 4,
                'role_id' => 3,
            ],
            4 => [
                'user_id' => 5,
                'role_id' => 1,
            ],
        ]);
    }
}