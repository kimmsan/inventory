<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoNonInvoicePaymentsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('non_invoice_payments')->delete();

        \DB::table('non_invoice_payments')->insert([
            0 => [
                'id' => 1,
                'slug' => '626d89b69b881',
                'amount' => 20000.0,
                'type' => 0,
                'date' => today(),
                'note' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'client_id' => 5,
                'transaction_id' => null,
                'created_by' => 1,
            ],
            1 => [
                'id' => 2,
                'slug' => '626d89cb3d5a2',
                'amount' => 5000.0,
                'type' => 1,
                'date' => today(),
                'note' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'client_id' => 5,
                'transaction_id' => 18,
                'created_by' => 1,
            ],
            2 => [
                'id' => 3,
                'slug' => '626d89d4e7134',
                'amount' => 30000.0,
                'type' => 0,
                'date' => today(),
                'note' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'client_id' => 4,
                'transaction_id' => null,
                'created_by' => 1,
            ],
            3 => [
                'id' => 4,
                'slug' => '626d89e7cf4c2',
                'amount' => 10000.0,
                'type' => 1,
                'date' => today(),
                'note' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'client_id' => 4,
                'transaction_id' => 19,
                'created_by' => 1,
            ],
        ]);
    }
}