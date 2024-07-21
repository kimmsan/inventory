<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoPurchasesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('purchases')->delete();

        \DB::table('purchases')->insert([
            0 => [
                'id' => 1,
                'purchase_no' => '1',
                'slug' => '626d3115d5c23',
                'discount' => 500.0,
                'transport' => 1000.0,
                'sub_total' => 29800.0,
                'po_reference' => 'PO Reference-1',
                'payment_terms' => 'Payment Terms-1',
                'po_date' => today(),
                'purchase_date' => today(),
                'note' => 'This is a note!',
                'status' => 1,
                'is_paid' => 0,
                'created_at' => now(),
                'updated_at' => now(),
                'supplier_id' => 5,
                'tax_id' => 2,
                'created_by' => 1,
            ],
            1 => [
                'id' => 2,
                'purchase_no' => '2',
                'slug' => '626d316b7bdd0',
                'discount' => null,
                'transport' => null,
                'sub_total' => 29800.0,
                'po_reference' => 'PO Reference-2',
                'payment_terms' => 'Payment Terms-2',
                'po_date' => today(),
                'purchase_date' => today(),
                'note' => 'This is a note!',
                'status' => 1,
                'is_paid' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'supplier_id' => 4,
                'tax_id' => 2,
                'created_by' => 1,
            ],
            2 => [
                'id' => 3,
                'purchase_no' => '3',
                'slug' => '626d31d82a276',
                'discount' => 200.0,
                'transport' => 500.0,
                'sub_total' => 31250.0,
                'po_reference' => 'PO Reference-3',
                'payment_terms' => 'Payment Terms-3',
                'po_date' => today(),
                'purchase_date' => today(),
                'note' => 'This is a  note!',
                'status' => 1,
                'is_paid' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'supplier_id' => 3,
                'tax_id' => 2,
                'created_by' => 1,
            ],
            3 => [
                'id' => 4,
                'purchase_no' => '4',
                'slug' => '626d321b0a902',
                'discount' => null,
                'transport' => null,
                'sub_total' => 31377.5,
                'po_reference' => 'PO Reference-4',
                'payment_terms' => 'Payment Terms-4',
                'po_date' => today(),
                'purchase_date' => today(),
                'note' => '',
                'status' => 1,
                'is_paid' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'supplier_id' => 2,
                'tax_id' => 3,
                'created_by' => 1,
            ],
            4 => [
                'id' => 5,
                'purchase_no' => '5',
                'slug' => '626d873f389ac',
                'discount' => null,
                'transport' => null,
                'sub_total' => 58600.0,
                'po_reference' => null,
                'payment_terms' => null,
                'po_date' => today(),
                'purchase_date' => today(),
                'note' => '',
                'status' => 1,
                'is_paid' => 0,
                'created_at' => now(),
                'updated_at' => now(),
                'supplier_id' => 2,
                'tax_id' => 2,
                'created_by' => 1,
            ],
        ]);
    }
}