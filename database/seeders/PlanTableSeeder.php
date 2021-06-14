<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanTableSeeder extends Seeder
{
    public function run()
    {
        Plan::firstOrCreate([
            'name' => 'Tender',
            'is_enabled' => 1,
        ]);

        Plan::firstOrCreate([
            'name' => 'Professional',
            'is_enabled' => 1,
        ]);

        Plan::firstOrCreate([
            'name' => 'Premium',
            'is_enabled' => 1,
        ]);

        Plan::firstOrCreate([
            'name' => 'Enterpise',
            'is_enabled' => 1,
        ]);

        Plan::firstOrCreate([
            'name' => 'Manufacture',
            'is_enabled' => 1,
        ]);
    }
}
