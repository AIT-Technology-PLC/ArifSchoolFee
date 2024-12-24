<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class Plans extends Seeder
{
    public function run()
    {
        Plan::updateOrCreate(
            ['name' => 'Standard'],
            ['is_enabled' => 1],
        );
    }
}
