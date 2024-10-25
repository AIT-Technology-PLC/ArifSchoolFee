<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Features extends Seeder
{
    private $features;

    public function run()
    {
        DB::transaction(function () {
            $this->seedFeatures();

            $this->v1Plans();
        });
    }

    private function seedFeatures()
    {
        Feature::updateOrCreate(
            ['name' => 'Merchandise Inventory'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Inventory History'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Gdn Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Grn Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Transfer Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Damage Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Inventory Adjustment'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Siv Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Sale Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Proforma Invoice'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Customer Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Credit Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Tender Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Purchase Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Supplier Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Product Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Warehouse Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'User Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'General Settings'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Notification Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Return Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Reservation Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Price Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Pad Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Custom Field Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Bill Of Material Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Job Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Employee Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Department Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Employee Transfer'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Warning Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Attendance Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Leave Management'],
            ['is_enabled' => 1]
        );
        Feature::updateOrCreate(
            ['name' => 'Advancement Management'],
            ['is_enabled' => 1]
        );
        Feature::updateOrCreate(
            ['name' => 'Expense Claim'],
            ['is_enabled' => 1]
        );
        Feature::updateOrCreate(
            ['name' => 'Announcement Management'],
            ['is_enabled' => 1]
        );
        Feature::updateOrCreate(
            ['name' => 'Compensation Management'],
            ['is_enabled' => 1]
        );
        Feature::updateOrCreate(
            ['name' => 'Compensation Adjustment'],
            ['is_enabled' => 1]
        );
        Feature::updateOrCreate(
            ['name' => 'Push Notification'],
            ['is_enabled' => 1]
        );
        Feature::updateOrCreate(
            ['name' => 'Debt Management'],
            ['is_enabled' => 1]
        );
        Feature::updateOrCreate(
            ['name' => 'Sales Report'],
            ['is_enabled' => 1]
        );
        Feature::updateOrCreate(
            ['name' => 'Return Report'],
            ['is_enabled' => 1]
        );
        Feature::updateOrCreate(
            ['name' => 'Expense Management'],
            ['is_enabled' => 1]
        );
        Feature::updateOrCreate(
            ['name' => 'Expense Report'],
            ['is_enabled' => 1]
        );
        Feature::updateOrCreate(
            ['name' => 'Customer Report'],
            ['is_enabled' => 1]
        );
        Feature::updateOrCreate(
            ['name' => 'Contact Management'],
            ['is_enabled' => 1]
        );
        Feature::updateOrCreate(
            ['name' => 'Price Increment'],
            ['is_enabled' => 1]
        );
        Feature::updateOrCreate(
            ['name' => 'Supplier Report'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Brand Management'],
            ['is_enabled' => 1]
        );
        Feature::updateOrCreate(
            ['name' => 'Item Type Management'],
            ['is_enabled' => 1]
        );
        Feature::updateOrCreate(
            ['name' => 'Payroll Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Daily Inventory Level Report'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Customer Deposit Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Inventory Transfer Report'],
            ['is_enabled' => 1]
        );
        Feature::updateOrCreate(
            ['name' => 'Credit Report'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Inventory Summary Report'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Inventory Valuation Report'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Cost Update Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Profit Report'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Inventory Valuation'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Sale By Payment Report'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Batch Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Inventory Batch Report'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Inventory In Transit Report'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Exchange Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Cost-Based Pricing'],
            ['is_enabled' => 1]
        );

        $this->features = Feature::all();
    }

    private function v1Plans()
    {
        $standard = Plan::firstWhere('name', 'standard');

        $standard->features()->sync(
            $this->features
                ->whereIn('name', [
                    'Merchandise Inventory',
                    'Inventory History',
                    'Gdn Management',
                    'Grn Management',
                    'Transfer Management',
                    'Siv Management',
                    'Proforma Invoice',
                    'Customer Management',
                    'Contact Management',
                    'Purchase Management',
                    'Supplier Management',
                    'Product Management',
                    'Brand Management',
                    'Warehouse Management',
                    'User Management',
                    'General Settings',
                    'Notification Management',
                    'Return Management',
                    'Push Notification',
                    'Sales Report',
                    'Item Type Management',
                ])
                ->pluck('id')
                ->toArray()
        );
    }
}
