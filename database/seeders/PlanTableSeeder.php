<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanTableSeeder extends Seeder
{
    public function run()
    {
        $plan = new Plan();

        $plan->create([
            'name' => 'Premium',
        ]);

        $plan->create([
            'name' => 'Professional',
        ]);

        $plan->create([
            'name' => 'Standard',
        ]);
    }
}
