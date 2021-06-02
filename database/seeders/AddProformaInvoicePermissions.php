<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AddProformaInvoicePermissions extends Seeder
{
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        DB::transaction(function () {
            $systemManager = Role::findByName('System Manager');
            $salesOfficer = Role::findByName('Sales Officer');
            $analyst = Role::findByName('Analyst');

            Permission::create(['name' => 'Create Proforma Invoice']);
            Permission::create(['name' => 'Read Proforma Invoice']);
            Permission::create(['name' => 'Update Proforma Invoice']);
            Permission::create(['name' => 'Delete Proforma Invoice']);
            Permission::create(['name' => 'Convert Proforma Invoice']);
            Permission::create(['name' => 'Cancel Proforma Invoice']);
            Permission::create(['name' => 'Delete Cancelled Proforma Invoice']);

            $systemManager->syncPermissions(Permission::all());

            $salesOfficer->givePermissionTo('Create Proforma Invoice');
            $salesOfficer->givePermissionTo('Read Proforma Invoice');
            $salesOfficer->givePermissionTo('Update Proforma Invoice');
            $salesOfficer->givePermissionTo('Delete Proforma Invoice');
            $salesOfficer->givePermissionTo('Convert Proforma Invoice');

            $analyst->givePermissionTo('Read Proforma Invoice');
        });
    }
}
