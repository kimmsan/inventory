<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoUserPermissionTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('user_permission')->delete();

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
