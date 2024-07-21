<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoExpensesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('expenses')->delete();

        \DB::table('expenses')->insert([
            0 => [
                'id' => 1,
                'reason' => 'Sticky Notes Purchase',
                'slug' => 'sticky-notes-purchase',
                'date' => today(),
                'note' => 'Sticky Notes Purchase for office use',
                'status' => 1,
                'image_path' => '',
                'created_at' => now(),
                'updated_at' => now(),
                'sub_cat_id' => 2,
                'transaction_id' => 8,
                'created_by' => 1,
            ],
            1 => [
                'id' => 2,
                'reason' => 'April Office Rent',
                'slug' => 'april-office-rent',
                'date' => today(),
                'note' => 'This is a note',
                'status' => 1,
                'image_path' => '',
                'created_at' => now(),
                'updated_at' => now(),
                'sub_cat_id' => 1,
                'transaction_id' => 9,
                'created_by' => 1,
            ],
        ]);
    }
}