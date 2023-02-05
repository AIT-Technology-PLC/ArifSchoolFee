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
            $tender = Plan::firstWhere('name', 'tender');

            $v2Starter = Plan::firstWhere('name', 'v2-starter');
            $v2Standard = Plan::firstWhere('name', 'v2-standard');
            $v2Professional = Plan::firstWhere('name', 'v2-professional');
            $v2Premium = Plan::firstWhere('name', 'v2-premium');
            $v2Production = Plan::firstWhere('name', 'v2-production');
            $v2Hr = Plan::firstWhere('name', 'v2-hr');

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

            $v2Starter->limits()->syncWithPivotValues([$warehouseLimit->id, $userLimit->id], ['amount' => 1]);

            $v2Standard->limits()->syncWithPivotValues([$warehouseLimit->id, $userLimit->id], ['amount' => 1]);

            $v2Professional->limits()->syncWithPivotValues([$warehouseLimit->id, $userLimit->id], ['amount' => 1]);

            $v2Premium->limits()->syncWithPivotValues([$warehouseLimit->id, $userLimit->id], ['amount' => 1]);

            $v2Production->limits()->syncWithPivotValues([$warehouseLimit->id, $userLimit->id], ['amount' => 1]);

            $v2Hr->limits()->syncWithPivotValues([$warehouseLimit->id, $userLimit->id], ['amount' => 1]);

            $tender->limits()->sync([
                $warehouseLimit->id => ['amount' => 1],
                $userLimit->id => ['amount' => 6],
            ]);
        });
    }
}
