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

            // Warehouse
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Warehouse']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Warehouse']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Warehouse']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Warehouse']);

            // Employee
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Employee']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Employee']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Employee']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Employee']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Employee']);

            // Company
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Company']);

            // Admin Panel
            $permissions[] = Permission::firstOrCreate(['name' => 'Manage Admin Panel Companies']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Manage Admin Panel Users']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Manage Admin Panel Subscriptions']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Manage Admin Panel Pads']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Manage Admin Panel Resets']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Manage Admin Panel Activation']);

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
