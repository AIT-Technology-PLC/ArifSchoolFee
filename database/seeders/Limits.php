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

            $this->v1Limits();
        });
    }

    private function v1Limits()
    {
        $standard = Plan::firstWhere('name', 'standard');

        $standard->limits()->sync([
            $this->warehouseLimit->id => ['amount' => 10],
            $this->userLimit->id => ['amount' => 10],
        ]);
    }
}
