<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Features extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
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
                ['is_enabled' => 0]
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
                ['name' => 'Bill Of Material Management'],
                ['is_enabled' => 1]
            );

            Feature::updateOrCreate(
                ['name' => 'Job Management'],
                ['is_enabled' => 1]
            );

            Feature::updateOrCreate(
                ['name' => 'Employee Management'],
                ['is_enabled' => 0]
            );

            Feature::updateOrCreate(
                ['name' => 'Department Management'],
                ['is_enabled' => 0]
            );

            Feature::updateOrCreate(
                ['name' => 'Employee Transfer'],
                ['is_enabled' => 0]
            );

            Feature::updateOrCreate(
                ['name' => 'Warning Management'],
                ['is_enabled' => 0]
            );

            Feature::updateOrCreate(
                ['name' => 'Attendance Management'],
                ['is_enabled' => 0]
            );

            Feature::updateOrCreate(
                ['name' => 'Leave Management'],
                ['is_enabled' => 0]
            );
            Feature::updateOrCreate(
                ['name' => 'Advancement Management'],
                ['is_enabled' => 0]
            );
            Feature::updateOrCreate(
                ['name' => 'Expense Claim'],
                ['is_enabled' => 0]
            );
            Feature::updateOrCreate(
                ['name' => 'Earning Management'],
                ['is_enabled' => 0]
            );
            Feature::updateOrCreate(
                ['name' => 'Announcement Management'],
                ['is_enabled' => 0]
            );
            Feature::updateOrCreate(
                ['name' => 'Compensation Management'],
                ['is_enabled' => 0]
            );
            Feature::updateOrCreate(
                ['name' => 'Compensation Adjustmen'],
                ['is_enabled' => 1]
            );

            $standard = Plan::firstWhere('name', 'standard');
            $professional = Plan::firstWhere('name', 'professional');
            $premium = Plan::firstWhere('name', 'premium');
            $tender = Plan::firstWhere('name', 'tender');

            $features = Feature::all();

            $standard->features()->sync(
                $features
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
                $features
                    ->whereNotIn('name', [
                        'Bill Of Material Management',
                        'Job Management',
                        'Tender Management',
                    ])
                    ->pluck('id')
                    ->toArray()
            );

            $premium->features()->sync(
                $features
                    ->whereNotIn('name', [
                        'Tender Management',
                    ])
                    ->pluck('id')
                    ->toArray()
            );

            $tender->features()->sync(
                $features
                    ->whereIn('name', [
                        'Proforma Invoice',
                        'Customer Management',
                        'Product Management',
                        'Warehouse Management',
                        'User Management',
                        'General Settings',
                        'Notification Management',
                        'Tender Management',
                    ])
                    ->pluck('id')
                    ->toArray()
            );
        });
    }
}