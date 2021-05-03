<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanTableSeeder extends Seeder
{
    public function run()
    {
        Plan::create(['name' => 'Premium']);

        Plan::create(['name' => 'Professional']);
        
        Plan::create(['name' => 'Standard']);
    }
}
