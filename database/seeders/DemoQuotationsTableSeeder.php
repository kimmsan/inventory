<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoQuotationsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('quotations')->delete();

        \DB::table('quotations')->insert([
            0 => [
                'id' => 1,
                'quotation_no' => '1',
                'slug' => '626d32f19370e',
                'reference' => 'Reference-001',
                'transport' => null,
                'discount_type' => 0,
                'discount' => null,
                'total_tax' => 1872.5,
                'sub_total' => 18725.0,
                'po_reference' => null,
                'payment_terms' => null,
                'delivery_place' => 'Dhaka, Bangladesh',
                'quotation_date' => now(),
                'note' => 'This is a note!',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'client_id' => 5,
                'tax_id' => 2,
                'created_by' => 1,
            ],
        ]);
    }
}