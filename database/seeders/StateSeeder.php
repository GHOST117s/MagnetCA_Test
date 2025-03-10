<?php

namespace Database\Seeders;

use App\Models\States;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $states = [
            ['name' => 'Delhi', 'charges' => 50, 'delivery_charges' => 30],
            ['name' => 'Maharashtra', 'charges' => 70, 'delivery_charges' => 40],
            ['name' => 'Uttar Pradesh', 'charges' => 60, 'delivery_charges' => 35],
        ];

        foreach ($states as $state) {
            States::create($state);
        }
    }
}
