<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoSuppliersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('suppliers')->delete();

        \DB::table('suppliers')->insert([
            0 => [
                'id' => 1,
                'name' => 'Yvonne Melendez',
                'slug' => 'yvonne-melendez',
                'supplier_id' => '1',
                'email' => 'novuty@mailinator.com',
                'phone' => '+1 (654) 921-9435',
                'company_name' => 'Newton Traders',
                'address' => 'Et deleniti alias do',
                'status' => 1,
                'image_path' => 'avatar.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            1 => [
                'id' => 2,
                'name' => 'Carla Bender',
                'slug' => 'carla-bender',
                'supplier_id' => '2',
                'email' => 'xabexived@mailinator.com',
                'phone' => '+1 (546) 502-6344',
                'company_name' => 'Richardson Inc',
                'address' => 'Eos perferendis aut',
                'status' => 1,
                'image_path' => 'avatar.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            2 => [
                'id' => 3,
                'name' => 'Quyn Erickson',
                'slug' => 'quyn-erickson',
                'supplier_id' => '3',
                'email' => 'qihykiwos@mailinator.com',
                'phone' => '+1 (951) 281-5524',
                'company_name' => 'Johns & Mcneil Co',
                'address' => 'Reprehenderit et lib',
                'status' => 1,
                'image_path' => 'avatar.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            3 => [
                'id' => 4,
                'name' => 'Amir Vega',
                'slug' => 'amir-vega',
                'supplier_id' => '4',
                'email' => 'birevagiv@mailinator.com',
                'phone' => '+1 (115) 102-4307',
                'company_name' => 'Huber Associates',
                'address' => 'Omnis adipisicing od',
                'status' => 1,
                'image_path' => 'avatar.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            4 => [
                'id' => 5,
                'name' => 'Jemima Hoffman',
                'slug' => 'jemima-hoffman',
                'supplier_id' => '5',
                'email' => 'hafyryhica@mailinator.com',
                'phone' => '+1 (403) 725-6412',
                'company_name' => 'Lawrence Plc',
                'address' => 'Voluptatibus esse ex',
                'status' => 1,
                'image_path' => 'avatar.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}