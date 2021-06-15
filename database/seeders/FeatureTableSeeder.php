<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeatureTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            Feature::firstOrCreate([
                'name' => 'Merchandise Inventory Level',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Inventory History',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Gdn',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Grn',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Transfer',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Damage',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Inventory Adjustments',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Siv',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Sales Invoice',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Proforma Invoices',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Customer Management',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Purchase Order',
                'is_enabled' => 1,
            ]);

            $tenderFeature = Feature::firstOrCreate([
                'name' => 'Tender Management',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Purchase Management',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Supplier Management',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Product Management',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'Warehouse Management',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'User Management',
                'is_enabled' => 1,
            ]);

            Feature::firstOrCreate([
                'name' => 'General Settings',
                'is_enabled' => 1,
            ]);

            $tender = Plan::where('name', 'tender')->first();
            $professional = Plan::where('name', 'professional')->first();
            $premium = Plan::where('name', 'premium')->first();
            $enterprise = Plan::where('name', 'enterprise')->first();
            $manufacture = Plan::where('name', 'manufacture')->first();

            $tender->features()->save($tenderFeature);

            $professional->features()->saveMany(Feature::all());

            $premium->features()->saveMany(Feature::all());

            $enterprise->features()->saveMany(Feature::all());

            $manufacture->features()->saveMany(Feature::all());
        });
    }
}
