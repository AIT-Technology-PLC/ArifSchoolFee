<?php

namespace Database\Seeders;

use App\Models\Limit;
use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Limits extends Seeder
{
    private $warehouseLimit;

    private $userLimit;

    private $salesReportEmailRecipientLimit;

    public function run()
    {
        DB::transaction(function () {
            $this->warehouseLimit = Limit::firstOrCreate(['name' => 'warehouse']);

            $this->userLimit = Limit::firstOrCreate(['name' => 'user']);

            $this->customFieldLimit = Limit::firstOrCreate(['name' => 'custom-field']);

            $this->salesReportEmailRecipientLimit = Limit::firstOrCreate(['name' => 'sales-report-email-recipient']);

            $this->v1Limits();

            $this->v2Limits();

            $this->v3Limits();
        });
    }

    private function v1Limits()
    {
        $standard = Plan::firstWhere('name', 'standard');
        $professional = Plan::firstWhere('name', 'professional');
        $premium = Plan::firstWhere('name', 'premium');
        $tender = Plan::firstWhere('name', 'tender');

        $standard->limits()->sync([
            $this->warehouseLimit->id => ['amount' => 2],
            $this->userLimit->id => ['amount' => 4],
        ]);

        $professional->limits()->sync([
            $this->warehouseLimit->id => ['amount' => 4],
            $this->userLimit->id => ['amount' => 6],
        ]);

        $premium->limits()->sync([
            $this->warehouseLimit->id => ['amount' => 6],
            $this->userLimit->id => ['amount' => 8],
        ]);

        $tender->limits()->sync([
            $this->warehouseLimit->id => ['amount' => 1],
            $this->userLimit->id => ['amount' => 6],
        ]);
    }

    private function v2Limits()
    {
        $v2Starter = Plan::firstWhere('name', 'v2-starter');
        $v2Standard = Plan::firstWhere('name', 'v2-standard');
        $v2Professional = Plan::firstWhere('name', 'v2-professional');
        $v2Premium = Plan::firstWhere('name', 'v2-premium');
        $v2Production = Plan::firstWhere('name', 'v2-production');
        $v2Hr = Plan::firstWhere('name', 'v2-hr');

        $v2Starter->limits()->syncWithPivotValues([$this->warehouseLimit->id, $this->userLimit->id], ['amount' => 1]);
        $v2Standard->limits()->syncWithPivotValues([$this->warehouseLimit->id, $this->userLimit->id], ['amount' => 1]);
        $v2Professional->limits()->syncWithPivotValues([$this->warehouseLimit->id, $this->userLimit->id], ['amount' => 1]);
        $v2Premium->limits()->syncWithPivotValues([$this->warehouseLimit->id, $this->userLimit->id], ['amount' => 1]);
        $v2Production->limits()->syncWithPivotValues([$this->warehouseLimit->id, $this->userLimit->id], ['amount' => 1]);
        $v2Hr->limits()->syncWithPivotValues([$this->warehouseLimit->id, $this->userLimit->id], ['amount' => 1]);
    }

    private function v3Limits()
    {
        $v3Pos = Plan::firstWhere('name', 'v3-pos');
        $v3Standard = Plan::firstWhere('name', 'v3-standard');
        $v3Professional = Plan::firstWhere('name', 'v3-professional');
        $v3Premium = Plan::firstWhere('name', 'v3-premium');

        $v3Pos->limits()->syncWithPivotValues([$this->warehouseLimit->id, $this->userLimit->id], ['amount' => 1]);
        $v3Standard->limits()->syncWithPivotValues([$this->warehouseLimit->id, $this->userLimit->id], ['amount' => 1]);
        $v3Professional->limits()->syncWithPivotValues([$this->warehouseLimit->id, $this->userLimit->id], ['amount' => 1]);
        $v3Premium->limits()->syncWithPivotValues([$this->warehouseLimit->id, $this->userLimit->id], ['amount' => 1]);
    }
}
