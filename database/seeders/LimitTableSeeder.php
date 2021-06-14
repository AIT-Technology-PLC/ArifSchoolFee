<?php

namespace Database\Seeders;

use App\Models\Limit;
use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LimitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::transaction(function () {
            $warehouseLimit = Limit::firstOrCreate(['name' => 'warehouse']);

            $tender = Plan::where('name', 'tender')->first();
            $professional = Plan::where('name', 'professional')->first();
            $premium = Plan::where('name', 'premium')->first();
            $enterprise = Plan::where('name', 'enterprise')->first();
            $manufacture = Plan::where('name', 'manufacture')->first();

            $warehouseLimit->plans()->save($tender, ['amount' => 0]);

            $warehouseLimit->plans()->save($professional, ['amount' => 2]);

            $warehouseLimit->plans()->save($premium, ['amount' => 5]);

            $warehouseLimit->plans()->save($enterprise, ['amount' => 10]);

            $warehouseLimit->plans()->save($manufacture, ['amount' => 5]);

        });
    }
}
