<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class Plans extends Seeder
{
    public function run()
    {
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
    }
}
