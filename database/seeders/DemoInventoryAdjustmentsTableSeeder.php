<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoInventoryAdjustmentsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('inventory_adjustments')->delete();

        \DB::table('inventory_adjustments')->insert([
            0 => [
                'id' => 1,
                'reason' => 'Warehouse cleanup',
                'slug' => 'warehouse-cleanup',
                'code' => 1,
                'date' => now(),
                'note' => 'This is a note',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
            ],
        ]);
    }
}