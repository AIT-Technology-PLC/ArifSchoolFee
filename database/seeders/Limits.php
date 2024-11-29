<?php

namespace Database\Seeders;

use App\Models\Limit;
use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Limits extends Seeder
{
    private $branchLimit;

    private $studentLimit;

    public function run()
    {
        DB::transaction(function () {
            $this->branchLimit = Limit::firstOrCreate(['name' => 'branch']);

            $this->studentLimit = Limit::firstOrCreate(['name' => 'student']);

            $this->v1Limits();
        });
    }

    private function v1Limits()
    {
        $standard = Plan::firstWhere('name', 'standard');

        $standard->limits()->sync([
            $this->branchLimit->id => ['amount' => 2],
            $this->studentLimit->id => ['amount' => 100],
        ]);
    }
}
