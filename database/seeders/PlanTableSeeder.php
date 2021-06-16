<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanTableSeeder extends Seeder
{
    public function run()
    {
        Plan::firstOrCreate([
            'name' => 'professional',
            'is_enabled' => 1,
        ]);

        Plan::firstOrCreate([
            'name' => 'premium',
            'is_enabled' => 1,
        ]);

        Plan::firstOrCreate([
            'name' => 'enterprise',
            'is_enabled' => 1,
        ]);
    }
}
