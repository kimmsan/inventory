<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // check if table is empty
        if (DB::table('user_permission')->count() == 0) {
            $permissions = DB::table('permissions')->get();
            foreach ($permissions as $permission) {
                DB::table('user_permission')->insert([
                    'user_id' => 1,
                    'permission_id' => $permission->id,
                ]);
            }
        }
    }
}