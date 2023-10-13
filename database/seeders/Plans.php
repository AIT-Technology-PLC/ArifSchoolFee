<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class Plans extends Seeder
{
    public function run()
    {
        // PRICE PLAN VERSION 1
        Plan::firstOrCreate([
            'name' => 'standard',
            'is_enabled' => 1,
        ]);

        Plan::firstOrCreate([
            'name' => 'professional',
            'is_enabled' => 1,
        ]);

        Plan::firstOrCreate([
            'name' => 'premium',
            'is_enabled' => 1,
        ]);

        Plan::firstOrCreate([
            'name' => 'tender',
            'is_enabled' => 1,
        ]);

        // PRICE PLAN VERSION 2
        Plan::firstOrCreate([
            'name' => 'v2-starter',
            'is_enabled' => 1,
        ]);

        Plan::firstOrCreate([
            'name' => 'v2-standard',
            'is_enabled' => 1,
        ]);

        Plan::firstOrCreate([
            'name' => 'v2-professional',
            'is_enabled' => 1,
        ]);

        Plan::firstOrCreate([
            'name' => 'v2-premium',
            'is_enabled' => 1,
        ]);

        Plan::firstOrCreate([
            'name' => 'v2-production',
            'is_enabled' => 1,
        ]);

        Plan::firstOrCreate([
            'name' => 'v2-hr',
            'is_enabled' => 1,
        ]);

        // PRICE PLAN VERSION 3
        Plan::firstOrCreate([
            'name' => 'v3-pos',
            'is_enabled' => 1,
        ]);

        Plan::firstOrCreate([
            'name' => 'v3-standard',
            'is_enabled' => 1,
        ]);

        Plan::firstOrCreate([
            'name' => 'v3-professional',
            'is_enabled' => 1,
        ]);

        Plan::firstOrCreate([
            'name' => 'v3-premium',
            'is_enabled' => 1,
        ]);
    }
}
