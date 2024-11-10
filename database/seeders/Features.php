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

            $this->deleteNonExistentFeatures();

            $this->v1Plans();
        });
    }

    private function seedFeatures()
    {
        Feature::updateOrCreate(
            ['name' => 'Branch Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Academic Year'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Section Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Class Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Route Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Vehicle Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'User Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Designation Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Department Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Staff Directory'],
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

        Feature::updateOrCreate(
            ['name' => 'Log Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Student Category'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Student Group'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Student Directory'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Fee Group'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Fee Type'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Fee Discount'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Fee Master'],
            ['is_enabled' => 1]
        );
        
        $this->features = Feature::all();
    }


    private function deleteNonExistentFeatures()
    {
        $currentFeatureNames  = [
            'Branch Management',
            'Academic Year',
            'Section Management',
            'Class Management',
            'Route Management',
            'Vehicle Management',
            'User Management',
            'Designation Management',
            'Department Management',
            'Staff Directory',
            'General Settings',
            'Notification Management',
            'Employee Management',
            'Push Notification',
            'Log Management',
            'Student Category',
            'Student Group',
            'Student Directory',
            'Fee Group',
            'Fee Type',
            'Fee Discount',
            'Fee Master',
        ];

        Feature::whereNotIn('name', $currentFeatureNames )->forceDelete();
    }

    private function v1Plans()
    {
        $standard = Plan::firstWhere('name', 'standard');

        $standard->features()->sync(
            $this->features
                ->whereIn('name', [
                    'Branch Management',
                    'Academic Year',
                    'Section Management',
                    'Class Management',
                    'Class Management',
                    'Fee Group',
                    'Fee Type',
                    'Fee Discount',
                    'Fee Master',
                    'Student Category',
                    'Student Group',
                    'Student Directory',
                    'Route Management',
                    'Vehicle Management',
                    'User Management',
                    'Designation Management',
                    'Department Management',
                    'Staff Directory',
                    'General Settings',
                    'Notification Management',
                    'Push Notification',
                    'Log Management',
                    'Employee Management',
                ])
                ->pluck('id')
                ->toArray()
        );
    }
}
