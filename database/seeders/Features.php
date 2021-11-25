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
                ['is_enabled' => 0]
            );

            Feature::updateOrCreate(
                ['name' => 'Purchase Order'],
                ['is_enabled' => 0]
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

            $professional = Plan::where('name', 'professional')->first();
            $premium = Plan::where('name', 'premium')->first();
            $enterprise = Plan::where('name', 'enterprise')->first();

            $professional->features()->sync(
                Feature::all()->pluck('id')->toArray()
            );

            $premium->features()->sync(
                Feature::all()->pluck('id')->toArray()
            );

            $enterprise->features()->sync(
                Feature::all()->pluck('id')->toArray()
            );
        });
    }
}
