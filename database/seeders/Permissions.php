<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class Permissions extends Seeder
{
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        DB::transaction(function () {
            $permissions = [];

            // Roles
            $systemManager = Role::firstOrCreate(['name' => 'System Manager']);

            // Branch
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Branch']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Branch']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Branch']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Branch']);

            // Academic Year
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Academic Year']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Academic Year']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Academic Year']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Academic Year']);

            // Section
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Section']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Section']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Section']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Section']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Section']);

            // Class
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Class']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Class']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Class']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Class']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Class']);

            // Route
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Route']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Route']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Route']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Route']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Route']);

            // Vehicle
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Vehicle']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Vehicle']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Vehicle']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Vehicle']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Vehicle']);

            // Employee
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Employee']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Employee']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Employee']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Employee']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Employee']);

            // Designation
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Designation']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Designation']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Designation']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Designation']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Designation']);

            // Department
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Department']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Department']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Department']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Department']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Department']);

            // Staff
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Staff']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Staff']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Staff']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Staff']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Staff']);

            // Fee Group
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Fee Group']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Fee Group']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Fee Group']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Fee Group']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Fee Group']);

            // Fee Type
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Fee Type']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Fee Type']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Fee Type']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Fee Type']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Fee Type']);

            // Fee Discount
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Fee Discount']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Fee Discount']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Fee Discount']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Fee Discount']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Assign Fee Discount']);

            // Fee Master
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Fee Master']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Fee Master']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Fee Master']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Fee Master']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Assign Fee Master']);

            // Collect Fee
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Collect Fee']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Collect Fee']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Search Fee Payment']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Search Fee Due']);

            // Account Management
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Account']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Account']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Account']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Account']);

            // Student Category
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Student Category']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Student Category']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Student Category']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Student Category']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Student Category']);

            // Student Group
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Student Group']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Student Group']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Student Group']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Student Group']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Student Group']);

            // Student
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Student']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Student']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Student']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Student']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Student']);

            // Student Promote
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Student Promote']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Student Promote']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Student Promote']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Student Promote']);

            // Company
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Company']);

            // Log
            $permissions[] = Permission::firstOrCreate(['name' => 'Read User Login Log']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Activity Log']);

            // Email and SMS 
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Message']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Message']);

            // Notice 
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Notice']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Notice']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Notice']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Notice']);

            // Admin Panel
            $permissions[] = Permission::firstOrCreate(['name' => 'Manage Admin Panel Companies']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Manage Admin Panel Users']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Manage Admin Panel Subscriptions']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Manage Admin Panel Pads']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Manage Admin Panel Resets']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Manage Admin Panel Activation']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Manage Admin Panel Setting']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Manage Admin Panel Payment']);

            //Report
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Student Report']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Student History Report']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Staff Report']);

            // Call Center and Bank
            $permissions[] = Permission::firstOrCreate(['name' => 'Manage Schools Payment']);

            // Delete Non-existent permissions
            Permission::whereNotIn('name', collect($permissions)->pluck('name'))->forceDelete();

            $superAdminPermissions = Permission::where('name', 'Manage Admin Panel Users')->get();

            User::permission($superAdminPermissions)->get()->each->syncPermissions(
                Permission::where('name', 'LIKE', 'Manage Admin Panel%')->get()
            );

            // Assign permissions to role
            $systemManager->syncPermissions(
                Permission::whereNot('name', 'LIKE', 'Manage Admin Panel%')
                ->whereNot('name', 'LIKE', 'Manage Schools Payment')
                ->get()
            );
        });
    }
}
