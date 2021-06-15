<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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

        Feature::firstOrCreate([
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
            'name' => 'User Mangement',
            'is_enabled' => 1,
        ]);

        Feature::firstOrCreate([
            'name' => 'General Settings',
            'is_enabled' => 1,
        ]);
    }
}
