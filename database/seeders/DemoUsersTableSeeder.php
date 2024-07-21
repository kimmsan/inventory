<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoUsersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->delete();

        \DB::table('users')->insert([
            0 => [
                'id' => 1,
                'name' => 'Super Admin',
                'email' => 'superadmin@acculance.com',
                'email_verified_at' => '2022-04-30 22:13:36',
                'password' => '$2y$10$m.UuOomrzepx2oWcTxzhv.bfzyH4nPqEO7VoNgxK3xIpMyqzTm4uy',
                'remember_token' => null,
                'account_role' => 1,
                'is_active' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
            1 => [
                'id' => 2,
                'name' => 'Whilemina Watts',
                'email' => 'rura@mailinator.com',
                'email_verified_at' => null,
                'password' => '$2y$10$jn0Si9GEEspQCwBtK1U19e398DDfSw0Iq/UrOobFj1XY9sfn8/R9q',
                'remember_token' => null,
                'account_role' => 0,
                'is_active' => 1,
                'created_at' => '2022-05-01 05:19:28',
                'updated_at' => '2022-05-01 05:19:28',
            ],
            2 => [
                'id' => 3,
                'name' => 'Mari Johns',
                'email' => 'sales@acculance.com',
                'email_verified_at' => null,
                'password' => '$2y$10$PuLuGojoP6frvUXjxWGHPegTk.ayyeIC0aBWLGS5ST.k1Chby9TPK',
                'remember_token' => null,
                'account_role' => 0,
                'is_active' => 1,
                'created_at' => '2022-05-14 14:14:40',
                'updated_at' => '2022-05-14 14:14:40',
            ],
            3 => [
                'id' => 4,
                'name' => 'Paki Wolf',
                'email' => 'manager@acculance.com',
                'email_verified_at' => null,
                'password' => '$2y$10$vN.8.hi/ShH7rjjdUe0Xz./5l8sZ9K/4nopOhPNBR4jjGs5tP/YOC',
                'remember_token' => null,
                'account_role' => 0,
                'is_active' => 1,
                'created_at' => '2022-05-15 10:28:43',
                'updated_at' => '2022-05-15 10:28:43',
            ],
            4 => [
                'id' => 5,
                'name' => 'Rafsan',
                'email' => 'developer@acculance.com',
                'email_verified_at' => null,
                'password' => '$2y$10$PWExVPMeeRvy7c1su9ZwTONGS4ZFJCj6lSWPgJMAEfbYgY.axJtNu',
                'remember_token' => null,
                'account_role' => 1,
                'is_active' => 1,
                'created_at' => '2022-05-15 09:14:11',
                'updated_at' => '2022-05-15 09:14:11',
            ],
        ]);
    }
}
