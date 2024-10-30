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
            ['name' => 'Employee Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Push Notification'],
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
                    'Warehouse Management',
                    'User Management',
                    'General Settings',
                    'Notification Management',
                    'Push Notification',
                ])
                ->pluck('id')
                ->toArray()
        );
    }
}
