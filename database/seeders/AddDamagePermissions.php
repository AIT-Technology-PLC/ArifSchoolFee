<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AddDamagePermissions extends Seeder
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

            Permission::create(['name' => 'Create Damage']);
            Permission::create(['name' => 'Read Damage']);
            Permission::create(['name' => 'Update Damage']);
            Permission::create(['name' => 'Delete Damage']);
            Permission::create(['name' => 'Approve Damage']);
            Permission::create(['name' => 'Subtract Damage']);
            Permission::create(['name' => 'Delete Approved Damage']);

            $systemManager->syncPermissions(Permission::all());

            $storeKeeper->givePermissionTo('Create Damage');
            $storeKeeper->givePermissionTo('Read Damage');
            $storeKeeper->givePermissionTo('Update Damage');
            $storeKeeper->givePermissionTo('Delete Damage');

            $analyst->givePermissionTo('Read Damage');
        });
    }
}
