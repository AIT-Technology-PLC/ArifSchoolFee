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
            ['name' => 'Staff Management'],
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
            ['name' => 'Email/SMS Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Notice Management'],
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
            ['name' => 'Student Management'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Student Promote'],
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

        Feature::updateOrCreate(
            ['name' => 'Collect Fee'],
            ['is_enabled' => 1]
        );

        Feature::updateOrCreate(
            ['name' => 'Account Management'],
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
            'Staff Management',
            'General Settings',
            'Notification Management',
            'Employee Management',
            'Push Notification',
            'Email/SMS Management',
            'Notice Management',
            'Log Management',
            'Student Category',
            'Student Group',
            'Student Management',
            'Student Promote',
            'Fee Group',
            'Fee Type',
            'Fee Discount',
            'Fee Master',
            'Collect Fee',
            'Account Management',
        ];

        Feature::whereNotIn('name', $currentFeatureNames )->forceDelete();
    }

    private function v1Plans()
    {
        $standard = Plan::firstWhere('name', 'Standard');

        $standard->features()->sync(
            $this->features
                ->whereIn('name', [
                    'Branch Management',
                    'Academic Year',
                    'Section Management',
                    'Class Management',
                    'Fee Group',
                    'Fee Type',
                    'Fee Discount',
                    'Fee Master',
                    'Collect Fee',
                    'Student Category',
                    'Student Group',
                    'Student Management',
                    'Student Promote',
                    'Route Management',
                    'Vehicle Management',
                    'User Management',
                    'Designation Management',
                    'Department Management',
                    'Staff Management',
                    'General Settings',
                    'Notification Management',
                    'Push Notification',
                    'Email/SMS Management',
                    'Notice Management',
                    'Log Management',
                    'Employee Management',
                    'Account Management',
                ])
                ->pluck('id')
                ->toArray()
        );
    }
}
