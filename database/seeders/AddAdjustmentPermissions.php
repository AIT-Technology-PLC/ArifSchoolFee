<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AddAdjustmentPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        DB::transaction(function () {
            $systemManager = Role::findByName('System Manager');
            $storeKeeper = Role::findByName('Store Keeper');
            $analyst = Role::findByName('Analyst');

            Permission::create(['name' => 'Create Adjustment']);
            Permission::create(['name' => 'Read Adjustment']);
            Permission::create(['name' => 'Update Adjustment']);
            Permission::create(['name' => 'Delete Adjustment']);
            Permission::create(['name' => 'Approve Adjustment']);
            Permission::create(['name' => 'Make Adjustment']);
            Permission::create(['name' => 'Delete Approved Adjustment']);

            $systemManager->syncPermissions(Permission::all());

            $storeKeeper->givePermissionTo('Create Adjustment');
            $storeKeeper->givePermissionTo('Read Adjustment');
            $storeKeeper->givePermissionTo('Update Adjustment');
            $storeKeeper->givePermissionTo('Make Adjustment');

            $analyst->givePermissionTo('Read Adjustment');
        });
    }
}