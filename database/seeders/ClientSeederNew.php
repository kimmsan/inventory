<?php

namespace Database\Seeders;

use App\Models\Client;
use Database\Factories\ClientFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeederNew extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clients = Client::factory(1000)->create();

        foreach ($clients->chunk(200) as $chunk) {
            Client::insert($chunk->toArray());
        }
    }
}
