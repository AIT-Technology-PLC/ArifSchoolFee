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
            $analyst = Role::firstOrCreate(['name' => 'Analyst']);
            $systemManager = Role::firstOrCreate(['name' => 'System Manager']);
            $custom = Role::firstOrCreate(['name' => 'Custom']);

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
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Academic Year']);

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
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Fee Discount']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Assign Fee Discount']);

            // Fee Master
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Fee Master']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Fee Master']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Fee Master']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Fee Master']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Fee Master']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Assign Fee Master']);

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
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Student Directory']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Student Directory']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Student Directory']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Student Directory']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Student Directory']);

            // Company
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Company']);

            //Log
            $permissions[] = Permission::firstOrCreate(['name' => 'Read User Login Log']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Activity Log']);

            // Admin Panel
            $permissions[] = Permission::firstOrCreate(['name' => 'Manage Admin Panel Companies']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Manage Admin Panel Users']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Manage Admin Panel Subscriptions']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Manage Admin Panel Pads']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Manage Admin Panel Resets']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Manage Admin Panel Activation']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Manage Admin Panel Setting']);

            // Delete Non-existent permissions
            Permission::whereNotIn('name', collect($permissions)->pluck('name'))->forceDelete();

            $superAdminPermissions = Permission::where('name', 'Manage Admin Panel Users')->get();

            User::permission($superAdminPermissions)->get()->each->syncPermissions(
                Permission::where('name', 'LIKE', 'Manage Admin Panel%')->get()
            );

            // Assign permissions to role
            $analyst->syncPermissions(Permission::where('name', 'like', 'Read%')->pluck('name'));

            $systemManager->syncPermissions(Permission::whereNot('name', 'LIKE', 'Manage Admin Panel%')->get());
        });
    }
}
