<?php

namespace Database\Seeders;

use App\Models\Limit;
use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Limits extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::transaction(function () {
            $standard = Plan::firstWhere('name', 'standard');
            $professional = Plan::firstWhere('name', 'professional');
            $premium = Plan::firstWhere('name', 'premium');

            $warehouseLimit = Limit::firstOrCreate(['name' => 'warehouse']);
            $userLimit = Limit::firstOrCreate(['name' => 'user']);

            $standard->limits()->sync([
                $warehouseLimit->id => ['amount' => 2],
                $userLimit->id => ['amount' => 4],
            ]);

            $professional->limits()->sync([
                $warehouseLimit->id => ['amount' => 4],
                $userLimit->id => ['amount' => 6],
            ]);

            $premium->limits()->sync([
                $warehouseLimit->id => ['amount' => 6],
                $userLimit->id => ['amount' => 8],
            ]);
        });
    }
}
