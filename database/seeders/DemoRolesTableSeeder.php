<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoRolesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('roles')->delete();

        \DB::table('roles')->insert([
            0 => [
                'id' => 1,
                'name' => 'Developer',
                'slug' => 'developer',
                'created_at' => null,
                'updated_at' => null,
            ],
            1 => [
                'id' => 2,
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'created_at' => null,
                'updated_at' => null,
            ],
            2 => [
                'id' => 3,
                'name' => 'Manager',
                'slug' => 'manager',
                'created_at' => '2022-04-30 22:14:14',
                'updated_at' => '2022-04-30 22:14:14',
            ],
            3 => [
                'id' => 4,
                'name' => 'Salesman',
                'slug' => 'salesman',
                'created_at' => '2022-05-14 14:11:36',
                'updated_at' => '2022-05-14 14:11:36',
            ],
        ]);
    }
}