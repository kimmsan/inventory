<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoPurchasePaymentsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('purchase_payments')->delete();

        \DB::table('purchase_payments')->insert([
            0 => [
                'id' => 1,
                'slug' => '626d316b863fe',
                'amount' => 25000.0,
                'discount' => null,
                'date' => today(),
                'note' => 'This is a note!',
                'status' => 1,
                'purchase_id' => 2,
                'transaction_id' => 10,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            1 => [
                'id' => 2,
                'slug' => '626d31d837a1b',
                'amount' => 30000.0,
                'discount' => null,
                'date' => today(),
                'note' => 'This is a  note!',
                'status' => 1,
                'purchase_id' => 3,
                'transaction_id' => 11,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            2 => [
                'id' => 3,
                'slug' => '626d321b0f5d2',
                'amount' => 31377.5,
                'discount' => null,
                'date' => today(),
                'note' => '',
                'status' => 1,
                'purchase_id' => 4,
                'transaction_id' => 12,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}