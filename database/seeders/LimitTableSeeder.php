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

            $professional = Plan::where('name', 'professional')->first();
            $premium = Plan::where('name', 'premium')->first();
            $enterprise = Plan::where('name', 'enterprise')->first();

            $warehouseLimit->plans()->save($professional, ['amount' => 2]);

            $warehouseLimit->plans()->save($premium, ['amount' => 5]);

            $warehouseLimit->plans()->save($enterprise, ['amount' => 10]);

        });
    }
}
