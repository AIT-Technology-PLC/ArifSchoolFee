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

            $warehouseLimit->plans()->sync([
                $professional->id => ['amount' => 2],
                $premium->id => ['amount' => 5],
                $enterprise->id => ['amount' => 10],
            ]);
        });
    }
}
