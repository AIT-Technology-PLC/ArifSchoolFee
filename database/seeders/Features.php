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

            $this->v2Plans();

            $this->v3Plans();
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
            ['is_enabled' => 0]
        );

        Feature::updateOrCreate(
            ['name' => 'Custom Field Management'],
            ['is_enabled' => 0]
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
            ['is_enabled' => 0]
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
        $professional = Plan::firstWhere('name', 'professional');
        $premium = Plan::firstWhere('name', 'premium');
        $tender = Plan::firstWhere('name', 'tender');

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
                ])
                ->pluck('id')
                ->toArray()
        );

        $professional->features()->sync(
            $this->features
                ->whereNotIn('name', [
                    'Pad Management',
                    'Custom Field Management',
                    'Bill Of Material Management',
                    'Job Management',
                    'Tender Management',
                ])
                ->pluck('id')
                ->toArray()
        );

        $premium->features()->sync(
            $this->features
                ->whereNotIn('name', [
                    'Pad Management',
                    'Custom Field Management',
                    'Tender Management',
                ])
                ->pluck('id')
                ->toArray()
        );

        $tender->features()->sync(
            $this->features
                ->whereIn('name', [
                    'Proforma Invoice',
                    'Customer Management',
                    'Contact Management',
                    'Product Management',
                    'Brand Management',
                    'Warehouse Management',
                    'User Management',
                    'General Settings',
                    'Notification Management',
                    'Tender Management',
                    'Push Notification',
                ])
                ->pluck('id')
                ->toArray()
        );
    }

    private function v2Plans()
    {
        $v2Starter = Plan::firstWhere('name', 'v2-starter');
        $v2Standard = Plan::firstWhere('name', 'v2-standard');
        $v2Professional = Plan::firstWhere('name', 'v2-professional');
        $v2Premium = Plan::firstWhere('name', 'v2-premium');
        $v2Production = Plan::firstWhere('name', 'v2-production');
        $v2Hr = Plan::firstWhere('name', 'v2-hr');

        $v2Starter->features()->sync(
            $this->features
                ->whereIn('name', [
                    'Merchandise Inventory',
                    'Inventory History',
                    'Warehouse Management',
                    'Grn Management',
                    'Gdn Management',
                    'Product Management',
                    'Sales Report',
                    'User Management',
                    'General Settings',
                    'Notification Management',
                    'Push Notification',
                ])
                ->pluck('id')
                ->toArray()
        );

        $v2Standard->features()->sync(
            $this->features
                ->whereIn('name', [
                    'Merchandise Inventory',
                    'Inventory History',
                    'Warehouse Management',
                    'Grn Management',
                    'Transfer Management',
                    'Product Management',
                    'Daily Inventory Level Report',
                    'User Management',
                    'General Settings',
                    'Notification Management',
                    'Push Notification',
                ])
                ->pluck('id')
                ->toArray()
        );

        $v2Professional->features()->sync(
            $this->features
                ->whereIn('name', [
                    'Merchandise Inventory',
                    'Inventory History',
                    'Warehouse Management',
                    'Grn Management',
                    'Transfer Management',
                    'Damage Management',
                    'Siv Management',
                    'Inventory Adjustment',
                    'Gdn Management',
                    'Proforma Invoice',
                    'Reservation Management',
                    'Customer Management',
                    'Product Management',
                    'Price Management',
                    'Price Increment',
                    'Credit Management',
                    'Sales Report',
                    'Daily Inventory Level Report',
                    'Inventory Transfer Report',
                    'Credit Report',
                    'User Management',
                    'General Settings',
                    'Notification Management',
                    'Push Notification',
                ])
                ->pluck('id')
                ->toArray()
        );

        $v2Premium->features()->sync(
            $this->features
                ->whereIn('name', [
                    'Merchandise Inventory',
                    'Inventory History',
                    'Warehouse Management',
                    'Grn Management',
                    'Transfer Management',
                    'Damage Management',
                    'Siv Management',
                    'Inventory Adjustment',
                    'Gdn Management',
                    'Proforma Invoice',
                    'Reservation Management',
                    'Return Management',
                    'Customer Management',
                    'Product Management',
                    'Price Management',
                    'Price Increment',
                    'Credit Management',
                    'Purchase Management',
                    'Supplier Management',
                    'Debt Management',
                    'Expense Management',
                    'Sales Report',
                    'Return Report',
                    'Customer Report',
                    'Expense Report',
                    'Daily Inventory Level Report',
                    'Customer Deposit Management',
                    'Inventory Transfer Report',
                    'Credit Report',
                    'User Management',
                    'General Settings',
                    'Notification Management',
                    'Push Notification',
                ])
                ->pluck('id')
                ->toArray()
        );

        $v2Production->features()->sync(
            $this->features
                ->whereIn('name', [
                    'Job Management',
                    'Bill Of Material Management',
                    'Merchandise Inventory',
                    'Inventory History',
                    'Warehouse Management',
                    'Grn Management',
                    'Transfer Management',
                    'Product Management',
                    'Daily Inventory Level Report',
                    'User Management',
                    'General Settings',
                    'Notification Management',
                    'Push Notification',
                ])
                ->pluck('id')
                ->toArray()
        );

        $v2Hr->features()->sync(
            $this->features
                ->whereIn('name', [
                    'Employee Management',
                    'Department Management',
                    'Employee Transfer',
                    'Warning Management',
                    'Attendance Management',
                    'Leave Management',
                    'Advancement Management',
                    'Expense Claim',
                    'Announcement Management',
                    'Compensation Management',
                    'Compensation Adjustment',
                    'Payroll Management',
                    'User Management',
                    'General Settings',
                    'Notification Management',
                    'Push Notification',
                ])
                ->pluck('id')
                ->toArray()
        );
    }

    private function v3Plans()
    {
        $v3Pos = Plan::firstWhere('name', 'v3-pos');
        $v3Standard = Plan::firstWhere('name', 'v3-standard');
        $v3Professional = Plan::firstWhere('name', 'v3-professional');
        $v3Premium = Plan::firstWhere('name', 'v3-premium');

        $v3Pos->features()->sync(
            $this->features
                ->whereIn('name', [
                    'Sale Management',
                    'Proforma Invoice',
                    'Return Management',
                    'Customer Management',
                    'Product Management',
                    'Price Management',
                    'Price Increment',
                    'Sales Report',
                    'Sale By Payment Report',
                    'Return Report',
                    'User Management',
                    'General Settings',
                    'Notification Management',
                    'Push Notification',
                    'Warehouse Management',
                ])
                ->pluck('id')
                ->toArray()
        );

        $v3Standard->features()->sync(
            $this->features
                ->whereIn('name', [
                    'Grn Management',
                    'Inventory Adjustment',
                    'Gdn Management',
                    'Proforma Invoice',
                    'Customer Management',
                    'Product Management',
                    'Price Management',
                    'Price Increment',
                    'Batch Management',
                    'Supplier Management',
                    'Merchandise Inventory',
                    'Inventory History',
                    'Inventory Summary Report',
                    'Sales Report',
                    'Sale By Payment Report',
                    'User Management',
                    'General Settings',
                    'Notification Management',
                    'Push Notification',
                    'Warehouse Management',
                ])
                ->pluck('id')
                ->toArray()
        );

        $v3Professional->features()->sync(
            $this->features
                ->whereIn('name', [
                    'Grn Management',
                    'Transfer Management',
                    'Damage Management',
                    'Inventory Adjustment',
                    'Siv Management',
                    'Sale Management',
                    'Gdn Management',
                    'Proforma Invoice',
                    'Return Management',
                    'Credit Management',
                    'Customer Management',
                    'Product Management',
                    'Brand Management',
                    'Price Management',
                    'Price Increment',
                    'Inventory Valuation',
                    'Cost-Based Pricing',
                    'Batch Management',
                    'Purchase Management',
                    'Supplier Management',
                    'Debt Management',
                    'Merchandise Inventory',
                    'Inventory History',
                    'Inventory Valuation Report',
                    'Inventory Summary Report',
                    'Inventory Batch Report',
                    'Profit Report',
                    'Customer Report',
                    'Sales Report',
                    'Sale By Payment Report',
                    'Return Report',
                    'Credit Report',
                    'Purchase Report',
                    'Supplier Report',
                    'User Management',
                    'General Settings',
                    'Notification Management',
                    'Push Notification',
                    'Warehouse Management',
                ])
                ->pluck('id')
                ->toArray()
        );

        $v3Premium->features()->sync(
            $this->features
                ->whereIn('name', [
                    'Grn Management',
                    'Transfer Management',
                    'Damage Management',
                    'Inventory Adjustment',
                    'Siv Management',
                    'Sale Management',
                    'Gdn Management',
                    'Proforma Invoice',
                    'Reservation Management',
                    'Return Management',
                    'Credit Management',
                    'Customer Management',
                    'Product Management',
                    'Brand Management',
                    'Price Management',
                    'Price Increment',
                    'Inventory Valuation',
                    'Cost-Based Pricing',
                    'Batch Management',
                    'Purchase Management',
                    'Supplier Management',
                    'Debt Management',
                    'Merchandise Inventory',
                    'Inventory History',
                    'Daily Inventory Level Report',
                    'Inventory Valuation Report',
                    'Inventory Summary Report',
                    'Inventory Batch Report',
                    'Profit Report',
                    'Customer Report',
                    'Sales Report',
                    'Sale By Payment Report',
                    'Return Report',
                    'Credit Report',
                    'Purchase Report',
                    'Supplier Report',
                    'Inventory In Transit Report',
                    'User Management',
                    'General Settings',
                    'Notification Management',
                    'Push Notification',
                    'Warehouse Management',
                ])
                ->pluck('id')
                ->toArray()
        );
    }
}
