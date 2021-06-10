<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
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

            // Roles
            $analyst = Role::create(['name' => 'Analyst']);
            $purchaseManager = Role::create(['name' => 'Purchase Manager']);
            $salesOfficer = Role::create(['name' => 'Sales Officer']);
            $storeKeeper = Role::create(['name' => 'Store Keeper']);
            $systemManager = Role::create(['name' => 'System Manager']);
            $tenderOfficer = Role::create(['name' => 'Tender Officer']);
            $userManager = Role::create(['name' => 'User Manager']);

            // GDN
            Permission::create(['name' => 'Create GDN']);
            Permission::create(['name' => 'Read GDN']);
            Permission::create(['name' => 'Update GDN']);
            Permission::create(['name' => 'Delete GDN']);
            Permission::create(['name' => 'Approve GDN']);
            Permission::create(['name' => 'Subtract GDN']);
            Permission::create(['name' => 'Delete Approved GDN']);

            // GRN
            Permission::create(['name' => 'Create GRN']);
            Permission::create(['name' => 'Read GRN']);
            Permission::create(['name' => 'Update GRN']);
            Permission::create(['name' => 'Delete GRN']);
            Permission::create(['name' => 'Approve GRN']);
            Permission::create(['name' => 'Add GRN']);
            Permission::create(['name' => 'Delete Approved GRN']);

            // Transfer
            Permission::create(['name' => 'Create Transfer']);
            Permission::create(['name' => 'Read Transfer']);
            Permission::create(['name' => 'Update Transfer']);
            Permission::create(['name' => 'Delete Transfer']);
            Permission::create(['name' => 'Approve Transfer']);
            Permission::create(['name' => 'Make Transfer']);
            Permission::create(['name' => 'Delete Approved Transfer']);

            // Damage
            Permission::create(['name' => 'Create Damage']);
            Permission::create(['name' => 'Read Damage']);
            Permission::create(['name' => 'Update Damage']);
            Permission::create(['name' => 'Delete Damage']);
            Permission::create(['name' => 'Approve Damage']);
            Permission::create(['name' => 'Subtract Damage']);
            Permission::create(['name' => 'Delete Approved Damage']);

            // SIV
            Permission::create(['name' => 'Create SIV']);
            Permission::create(['name' => 'Read SIV']);
            Permission::create(['name' => 'Update SIV']);
            Permission::create(['name' => 'Delete SIV']);
            Permission::create(['name' => 'Approve SIV']);
            Permission::create(['name' => 'Execute SIV']);
            Permission::create(['name' => 'Delete Approved SIV']);

            // Merchandise
            Permission::create(['name' => 'Create Merchandise']);
            Permission::create(['name' => 'Read Merchandise']);
            Permission::create(['name' => 'Update Merchandise']);
            Permission::create(['name' => 'Delete Merchandise']);

            // Sale
            Permission::create(['name' => 'Create Sale']);
            Permission::create(['name' => 'Read Sale']);
            Permission::create(['name' => 'Update Sale']);
            Permission::create(['name' => 'Delete Sale']);
            Permission::create(['name' => 'Approve Sale']);
            Permission::create(['name' => 'Delete Approved Sale']);

            // Proforma Invoice
            Permission::create(['name' => 'Create Proforma Invoice']);
            Permission::create(['name' => 'Read Proforma Invoice']);
            Permission::create(['name' => 'Update Proforma Invoice']);
            Permission::create(['name' => 'Delete Proforma Invoice']);
            Permission::create(['name' => 'Convert Proforma Invoice']);
            Permission::create(['name' => 'Cancel Proforma Invoice']);
            Permission::create(['name' => 'Delete Cancelled Proforma Invoice']);

            // Purchase
            Permission::create(['name' => 'Create Purchase']);
            Permission::create(['name' => 'Read Purchase']);
            Permission::create(['name' => 'Update Purchase']);
            Permission::create(['name' => 'Delete Purchase']);
            Permission::create(['name' => 'Approve Purchase']);
            Permission::create(['name' => 'Delete Approved Purchase']);

            // PO
            Permission::create(['name' => 'Create PO']);
            Permission::create(['name' => 'Read PO']);
            Permission::create(['name' => 'Update PO']);
            Permission::create(['name' => 'Delete PO']);
            Permission::create(['name' => 'Approve PO']);
            Permission::create(['name' => 'Delete Approved PO']);

            // Product
            Permission::create(['name' => 'Create Product']);
            Permission::create(['name' => 'Read Product']);
            Permission::create(['name' => 'Update Product']);
            Permission::create(['name' => 'Delete Product']);

            // Warehouse
            Permission::create(['name' => 'Create Warehouse']);
            Permission::create(['name' => 'Read Warehouse']);
            Permission::create(['name' => 'Update Warehouse']);
            Permission::create(['name' => 'Delete Warehouse']);

            // Employee
            Permission::create(['name' => 'Create Employee']);
            Permission::create(['name' => 'Read Employee']);
            Permission::create(['name' => 'Update Employee']);
            Permission::create(['name' => 'Delete Employee']);

            // Supplier
            Permission::create(['name' => 'Create Supplier']);
            Permission::create(['name' => 'Read Supplier']);
            Permission::create(['name' => 'Update Supplier']);
            Permission::create(['name' => 'Delete Supplier']);

            // Customer
            Permission::create(['name' => 'Create Customer']);
            Permission::create(['name' => 'Read Customer']);
            Permission::create(['name' => 'Update Customer']);
            Permission::create(['name' => 'Delete Customer']);

            // Tender
            Permission::create(['name' => 'Create Tender']);
            Permission::create(['name' => 'Read Tender']);
            Permission::create(['name' => 'Update Tender']);
            Permission::create(['name' => 'Delete Tender']);

            // Price
            Permission::create(['name' => 'Create Price']);
            Permission::create(['name' => 'Read Price']);
            Permission::create(['name' => 'Update Price']);
            Permission::create(['name' => 'Delete Price']);

            // Company
            Permission::create(['name' => 'Update Company']);

            // Assign permissions to role
            $analyst->syncPermissions([
                'Read GDN',
                'Read GRN',
                'Read PO',
                'Read Purchase',
                'Read Sale',
                'Read Proforma Invoice',
                'Read Damage',
                'Read SIV',
            ]);

            $purchaseManager->syncPermissions([
                'Create Purchase',
                'Create Supplier',
                'Read Product',
                'Read Purchase',
                'Read Supplier',
                'Read Damage',
                'Update Purchase',
                'Update Supplier',
            ]);

            $salesOfficer->syncPermissions([
                'Create Customer',
                'Create GDN',
                'Create PO',
                'Create Sale',
                'Create Proforma Invoice',
                'Read Customer',
                'Read GDN',
                'Read PO',
                'Read Product',
                'Read Sale',
                'Read Proforma Invoice',
                'Update Customer',
                'Update GDN',
                'Update PO',
                'Update Sale',
                'Update Proforma Invoice',
                'Convert Proforma Invoice',
            ]);

            $storeKeeper->syncPermissions([
                'Add GRN',
                'Create GRN',
                'Create Merchandise',
                'Create Transfer',
                'Create Damage',
                'Make Transfer',
                'Read GRN',
                'Read Merchandise',
                'Read Product',
                'Read Transfer',
                'Read Warehouse',
                'Read Damage',
                'Update GRN',
                'Update Merchandise',
                'Update Transfer',
                'Update Damage',
            ]);

            $systemManager->syncPermissions(Permission::all());

            $tenderOfficer->syncPermissions([
                'Create Tender',
                'Read Tender',
                'Update Tender',
            ]);

            $userManager->syncPermissions([
                'Create Employee',
                'Read Employee',
                'Update Employee',
            ]);
        });
    }
}
