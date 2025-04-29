<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create([
            'product_id' => "price_1RJ8KvFVv0uquNwOEgcKaL2F",
            'name'       => 'Post Per Day',
            'price'      => '1',
        ]);
        Plan::create([
            'product_id' => "price_1RJ8KVFVv0uquNwOEETPBpDD",
            'name'       => 'Post Per Week',
            'price'      => '7',
        ]);
        Plan::create([
            'product_id' => "price_1RJ8KvFVv0uquNwOEgcKaL2F",
            'name'       => 'Post Per Month',
            'price'      => '30',
        ]);

        Plan::create([
            'product_id' => "price_1P1sLZI5A9nxu5SN3xA1t7u0",
            'name'       => 'Weekly Package',
            'price'      => '10',
        ]);
        Plan::create([
            'product_id' => "price_1P1slxI5A9nxu5SNKVUvj3P0",
            'name'       => 'Monthly Package',
            'price'      => '7',
        ]);
        Plan::create([
            'product_id' => "price_1P1sOMI5A9nxu5SNTtL6t9Ix",
            'name'       => 'Annual Package',
            'price'      => '90',
        ]);
    }
}
