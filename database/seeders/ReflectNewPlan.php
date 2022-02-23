<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Feature;
use App\Models\Limit;
use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReflectNewPlan extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $this->updateCompanies();
            $this->updatePlans();
            $this->updateLimits();
            $this->updateFeatures();
        });
    }

    private function updateCompanies()
    {
        Company::whereNotNull('plan_id')->update(['plan_id' => 5]);
    }

    private function updatePlans()
    {
        Plan::where('name', 'professional')->update(['name' => 'standard']);
        Plan::where('name', 'premium')->update(['name' => 'professional']);

        $enterprise = Plan::firstWhere('name', 'enterprise');
        $enterprise->features()->detach();
        $enterprise->limits()->detach();
        $enterprise->forceDelete();
    }

    private function updateLimits()
    {
        $standard = Plan::firstWhere('name', 'standard');
        $professional = Plan::firstWhere('name', 'professional');

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
    }

    private function updateFeatures()
    {
        $standard = Plan::firstWhere('name', 'standard');
        $professional = Plan::firstWhere('name', 'professional');

        $standard->features()->sync(
            Feature::query()
                ->whereIn('name', [
                    'Merchandise Inventory',
                    'Inventory History',
                    'Gdn Management',
                    'Grn Management',
                    'Transfer Management',
                    'Inventory Adjustment',
                    'Proforma Invoice',
                    'Customer Management',
                    'Purchase Management',
                    'Supplier Management',
                    'Product Management',
                    'Warehouse Management',
                    'User Management',
                    'General Settings',
                    'Notification Management',
                    'Return Management',
                ])
                ->pluck('id')
                ->toArray()
        );

        $professional->features()->sync(
            Feature::all()->pluck('id')->toArray()
        );
    }
}
