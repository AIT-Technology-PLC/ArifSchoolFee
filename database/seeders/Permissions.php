<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class Permissions extends Seeder
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
            $analyst = Role::firstOrCreate(['name' => 'Analyst']);
            $purchaseManager = Role::firstOrCreate(['name' => 'Purchase Manager']);
            $salesOfficer = Role::firstOrCreate(['name' => 'Sales Officer']);
            $storeKeeper = Role::firstOrCreate(['name' => 'Store Keeper']);
            $systemManager = Role::firstOrCreate(['name' => 'System Manager']);
            $tenderOfficer = Role::firstOrCreate(['name' => 'Tender Officer']);
            $userManager = Role::firstOrCreate(['name' => 'User Manager']);

            // GDN
            Permission::firstOrCreate(['name' => 'Create GDN']);
            Permission::firstOrCreate(['name' => 'Read GDN']);
            Permission::firstOrCreate(['name' => 'Update GDN']);
            Permission::firstOrCreate(['name' => 'Delete GDN']);
            Permission::firstOrCreate(['name' => 'Approve GDN']);
            Permission::firstOrCreate(['name' => 'Subtract GDN']);
            Permission::firstOrCreate(['name' => 'Close GDN']);
            Permission::firstOrCreate(['name' => 'Delete Approved GDN']);

            // GRN
            Permission::firstOrCreate(['name' => 'Create GRN']);
            Permission::firstOrCreate(['name' => 'Read GRN']);
            Permission::firstOrCreate(['name' => 'Update GRN']);
            Permission::firstOrCreate(['name' => 'Delete GRN']);
            Permission::firstOrCreate(['name' => 'Approve GRN']);
            Permission::firstOrCreate(['name' => 'Add GRN']);
            Permission::firstOrCreate(['name' => 'Delete Approved GRN']);

            // Transfer
            Permission::firstOrCreate(['name' => 'Create Transfer']);
            Permission::firstOrCreate(['name' => 'Read Transfer']);
            Permission::firstOrCreate(['name' => 'Update Transfer']);
            Permission::firstOrCreate(['name' => 'Delete Transfer']);
            Permission::firstOrCreate(['name' => 'Approve Transfer']);
            Permission::firstOrCreate(['name' => 'Make Transfer']);
            Permission::firstOrCreate(['name' => 'Close Transfer']);
            Permission::firstOrCreate(['name' => 'Delete Approved Transfer']);

            // Damage
            Permission::firstOrCreate(['name' => 'Create Damage']);
            Permission::firstOrCreate(['name' => 'Read Damage']);
            Permission::firstOrCreate(['name' => 'Update Damage']);
            Permission::firstOrCreate(['name' => 'Delete Damage']);
            Permission::firstOrCreate(['name' => 'Approve Damage']);
            Permission::firstOrCreate(['name' => 'Subtract Damage']);
            Permission::firstOrCreate(['name' => 'Delete Approved Damage']);

            // Adjustment
            Permission::firstOrCreate(['name' => 'Create Adjustment']);
            Permission::firstOrCreate(['name' => 'Read Adjustment']);
            Permission::firstOrCreate(['name' => 'Update Adjustment']);
            Permission::firstOrCreate(['name' => 'Delete Adjustment']);
            Permission::firstOrCreate(['name' => 'Approve Adjustment']);
            Permission::firstOrCreate(['name' => 'Make Adjustment']);
            Permission::firstOrCreate(['name' => 'Delete Approved Adjustment']);

            // Return
            Permission::firstOrCreate(['name' => 'Create Return']);
            Permission::firstOrCreate(['name' => 'Read Return']);
            Permission::firstOrCreate(['name' => 'Update Return']);
            Permission::firstOrCreate(['name' => 'Delete Return']);
            Permission::firstOrCreate(['name' => 'Approve Return']);
            Permission::firstOrCreate(['name' => 'Make Return']);
            Permission::firstOrCreate(['name' => 'Delete Approved Return']);

            // SIV
            Permission::firstOrCreate(['name' => 'Create SIV']);
            Permission::firstOrCreate(['name' => 'Read SIV']);
            Permission::firstOrCreate(['name' => 'Update SIV']);
            Permission::firstOrCreate(['name' => 'Delete SIV']);
            Permission::firstOrCreate(['name' => 'Approve SIV']);
            Permission::firstOrCreate(['name' => 'Delete Approved SIV']);

            // Merchandise
            Permission::firstOrCreate(['name' => 'Create Merchandise']);
            Permission::firstOrCreate(['name' => 'Read Merchandise']);
            Permission::firstOrCreate(['name' => 'Update Merchandise']);
            Permission::firstOrCreate(['name' => 'Delete Merchandise']);

            // Sale
            Permission::firstOrCreate(['name' => 'Create Sale']);
            Permission::firstOrCreate(['name' => 'Read Sale']);
            Permission::firstOrCreate(['name' => 'Update Sale']);
            Permission::firstOrCreate(['name' => 'Delete Sale']);
            Permission::firstOrCreate(['name' => 'Approve Sale']);
            Permission::firstOrCreate(['name' => 'Delete Approved Sale']);

            // Reservation
            Permission::firstOrCreate(['name' => 'Create Reservation']);
            Permission::firstOrCreate(['name' => 'Read Reservation']);
            Permission::firstOrCreate(['name' => 'Update Reservation']);
            Permission::firstOrCreate(['name' => 'Delete Reservation']);
            Permission::firstOrCreate(['name' => 'Approve Reservation']);
            Permission::firstOrCreate(['name' => 'Cancel Reservation']);
            Permission::firstOrCreate(['name' => 'Convert Reservation']);
            Permission::firstOrCreate(['name' => 'Make Reservation']);
            Permission::firstOrCreate(['name' => 'Delete Approved Reservation']);

            // Proforma Invoice
            Permission::firstOrCreate(['name' => 'Create Proforma Invoice']);
            Permission::firstOrCreate(['name' => 'Read Proforma Invoice']);
            Permission::firstOrCreate(['name' => 'Update Proforma Invoice']);
            Permission::firstOrCreate(['name' => 'Delete Proforma Invoice']);
            Permission::firstOrCreate(['name' => 'Convert Proforma Invoice']);
            Permission::firstOrCreate(['name' => 'Cancel Proforma Invoice']);
            Permission::firstOrCreate(['name' => 'Close Proforma Invoice']);
            Permission::firstOrCreate(['name' => 'Delete Cancelled Proforma Invoice']);

            // Purchase
            Permission::firstOrCreate(['name' => 'Create Purchase']);
            Permission::firstOrCreate(['name' => 'Read Purchase']);
            Permission::firstOrCreate(['name' => 'Update Purchase']);
            Permission::firstOrCreate(['name' => 'Delete Purchase']);
            Permission::firstOrCreate(['name' => 'Approve Purchase']);
            Permission::firstOrCreate(['name' => 'Close Purchase']);
            Permission::firstOrCreate(['name' => 'Delete Approved Purchase']);

            // PO
            Permission::firstOrCreate(['name' => 'Create PO']);
            Permission::firstOrCreate(['name' => 'Read PO']);
            Permission::firstOrCreate(['name' => 'Update PO']);
            Permission::firstOrCreate(['name' => 'Delete PO']);
            Permission::firstOrCreate(['name' => 'Approve PO']);
            Permission::firstOrCreate(['name' => 'Delete Approved PO']);

            // Product
            Permission::firstOrCreate(['name' => 'Create Product']);
            Permission::firstOrCreate(['name' => 'Read Product']);
            Permission::firstOrCreate(['name' => 'Update Product']);
            Permission::firstOrCreate(['name' => 'Delete Product']);

            // Warehouse
            Permission::firstOrCreate(['name' => 'Create Warehouse']);
            Permission::firstOrCreate(['name' => 'Read Warehouse']);
            Permission::firstOrCreate(['name' => 'Update Warehouse']);
            Permission::firstOrCreate(['name' => 'Delete Warehouse']);

            // Employee
            Permission::firstOrCreate(['name' => 'Create Employee']);
            Permission::firstOrCreate(['name' => 'Read Employee']);
            Permission::firstOrCreate(['name' => 'Update Employee']);
            Permission::firstOrCreate(['name' => 'Delete Employee']);

            // Supplier
            Permission::firstOrCreate(['name' => 'Create Supplier']);
            Permission::firstOrCreate(['name' => 'Read Supplier']);
            Permission::firstOrCreate(['name' => 'Update Supplier']);
            Permission::firstOrCreate(['name' => 'Delete Supplier']);

            // Customer
            Permission::firstOrCreate(['name' => 'Create Customer']);
            Permission::firstOrCreate(['name' => 'Read Customer']);
            Permission::firstOrCreate(['name' => 'Update Customer']);
            Permission::firstOrCreate(['name' => 'Delete Customer']);

            // Credit
            Permission::firstOrCreate(['name' => 'Create Credit']);
            Permission::firstOrCreate(['name' => 'Read Credit']);
            Permission::firstOrCreate(['name' => 'Update Credit']);
            Permission::firstOrCreate(['name' => 'Delete Credit']);
            Permission::firstOrCreate(['name' => 'Settle Credit']);

            // Tender
            Permission::firstOrCreate(['name' => 'Create Tender']);
            Permission::firstOrCreate(['name' => 'Read Tender']);
            Permission::firstOrCreate(['name' => 'Update Tender']);
            Permission::firstOrCreate(['name' => 'Delete Tender']);
            Permission::firstOrCreate(['name' => 'Read Tender Sensitive Data']);
            Permission::firstOrCreate(['name' => 'Assign Tender Checklists']);

            // Company
            Permission::firstOrCreate(['name' => 'Update Company']);

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
                'Read Adjustment',
                'Read Return',
                'Read Merchandise',
                'Read Product',
                'Read Supplier',
                'Read Customer',
                'Read Credit',
                'Read Reservation',
            ]);

            $purchaseManager->syncPermissions([
                'Create Purchase',
                'Create Supplier',
                'Read Purchase',
                'Read Supplier',
                'Read Product',
                'Update Purchase',
                'Update Supplier',
            ]);

            $salesOfficer->syncPermissions([
                'Create GDN',
                'Create Sale',
                'Create Proforma Invoice',
                'Create Customer',
                'Create Credit',
                'Create PO',
                'Create Return',
                'Create Reservation',
                'Read GDN',
                'Read Sale',
                'Read Proforma Invoice',
                'Read Customer',
                'Read Credit',
                'Read PO',
                'Read Return',
                'Read Product',
                'Read Reservation',
                'Update GDN',
                'Update Sale',
                'Update Proforma Invoice',
                'Update Customer',
                'Update Credit',
                'Update PO',
                'Update Return',
                'Update Reservation',
                'Convert Proforma Invoice',
                'Convert Reservation',
            ]);

            $storeKeeper->syncPermissions([
                'Create GRN',
                'Create Merchandise',
                'Create Transfer',
                'Create Damage',
                'Create Adjustment',
                'Create SIV',
                'Read GRN',
                'Read Merchandise',
                'Read Transfer',
                'Read Damage',
                'Read Adjustment',
                'Read Return',
                'Read SIV',
                'Read Product',
                'Read Warehouse',
                'Read GDN',
                'Read Reservation',
                'Update GRN',
                'Update Merchandise',
                'Update Transfer',
                'Update Damage',
                'Update Adjustment',
                'Update SIV',
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
