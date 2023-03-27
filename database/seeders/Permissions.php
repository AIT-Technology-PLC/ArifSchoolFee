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
        $permissions = [];

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        DB::transaction(function () {

            // Roles
            $analyst = Role::firstOrCreate(['name' => 'Analyst']);
            $humanResourceManager = Role::firstOrCreate(['name' => 'Human Resource Manager']);
            $humanResourceOfficer = Role::firstOrCreate(['name' => 'Human Resource Officer']);
            $purchaseManager = Role::firstOrCreate(['name' => 'Purchase Manager']);
            $salesOfficer = Role::firstOrCreate(['name' => 'Sales Officer']);
            $storeKeeper = Role::firstOrCreate(['name' => 'Store Keeper']);
            $systemManager = Role::firstOrCreate(['name' => 'System Manager']);
            $tenderOfficer = Role::firstOrCreate(['name' => 'Tender Officer']);
            $userManager = Role::firstOrCreate(['name' => 'User Manager']);
            $custom = Role::firstOrCreate(['name' => 'Custom']);

            // GDN
            $permissions[] = Permission::firstOrCreate(['name' => 'Create GDN']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read GDN']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update GDN']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete GDN']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Approve GDN']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Subtract GDN']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Close GDN']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Cancel GDN']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Approved GDN']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import GDN']);

            // GRN
            $permissions[] = Permission::firstOrCreate(['name' => 'Create GRN']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read GRN']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update GRN']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete GRN']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Approve GRN']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Add GRN']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Approved GRN']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import GRN']);

            // Transfer
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Transfer']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Transfer']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Transfer']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Transfer']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Approve Transfer']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Make Transfer']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Close Transfer']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Approved Transfer']);

            // Damage
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Damage']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Damage']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Damage']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Damage']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Approve Damage']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Subtract Damage']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Approved Damage']);

            // Adjustment
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Adjustment']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Adjustment']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Adjustment']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Adjustment']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Approve Adjustment']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Make Adjustment']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Approved Adjustment']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Adjustment']);

            // Return
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Return']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Return']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Return']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Return']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Approve Return']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Make Return']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Approved Return']);

            // SIV
            $permissions[] = Permission::firstOrCreate(['name' => 'Create SIV']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read SIV']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update SIV']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete SIV']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Approve SIV']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Approved SIV']);

            // Merchandise
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Available Inventory']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Reserved Inventory']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Work In Process Inventory']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read On Hand Inventory']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Out Of Stock Inventory']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Expired Inventory']);

            // Sale
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Sale']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Sale']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Sale']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Sale']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Approve Sale']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Cancel Sale']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Approved Sale']);

            // Reservation
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Reservation']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Reservation']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Reservation']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Reservation']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Approve Reservation']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Cancel Reservation']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Convert Reservation']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Make Reservation']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Approved Reservation']);

            // Proforma Invoice
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Proforma Invoice']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Proforma Invoice']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Proforma Invoice']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Proforma Invoice']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Convert Proforma Invoice']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Cancel Proforma Invoice']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Close Proforma Invoice']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Cancelled Proforma Invoice']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Restore Proforma Invoice']);

            // Purchase
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Purchase']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Purchase']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Purchase']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Purchase']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Approve Purchase']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Make Purchase']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Close Purchase']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Approved Purchase']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Reject Purchase']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Cancel Purchase']);

            // Product
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Product']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Product']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Product']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Product']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Product']);

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

            // Supplier
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Supplier']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Supplier']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Supplier']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Supplier']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Supplier']);

            // Customer
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Customer']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Customer']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Customer']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Customer']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Customer']);

            // Credit
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Credit']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Credit']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Credit']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Credit']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Settle Credit']);

            // Tender
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Tender']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Tender']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Tender']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Tender']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Tender Sensitive Data']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Assign Tender Checklists']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Tender']);

            // Price
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Price']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Price']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Price']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Price']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Price']);

            // Pad
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Pad']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Pad']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Pad']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Pad']);

            // Company
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Company']);

            // Bill Of Material
            $permissions[] = Permission::firstOrCreate(['name' => 'Create BOM']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read BOM']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update BOM']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete BOM']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Approve BOM']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import BOM']);

            //Job
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Job']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Job']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Job']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Job']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Approve Job']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Add Extra Job']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Subtract Extra Job']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Plan Job']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Close Job']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Wip Job']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Available Job']);

            //Department
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Department']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Department']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Department']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Department']);

            //Employee Transfer
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Employee Transfer']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Employee Transfer']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Employee Transfer']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Employee Transfer']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Approve Employee Transfer']);

            //Warning
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Warning']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Warning']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Warning']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Warning']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Approve Warning']);

            //Attendance
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Attendance']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Attendance']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Attendance']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Attendance']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Approve Attendance']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Cancel Attendance']);

            //Advancement
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Advancement']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Advancement']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Advancement']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Advancement']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Approve Advancement']);

            //Leave
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Leave']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Leave']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Leave']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Leave']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Approve Leave']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Cancel Leave']);

            // Other
            $permissions[] = Permission::firstOrCreate(['name' => 'Convert To Credit']);

            //Expense Claim
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Expense Claim']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Expense Claim']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Expense Claim']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Expense Claim']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Approve Expense Claim']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Reject Expense Claim']);

            //Announcement
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Announcement']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Announcement']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Announcement']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Announcement']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Approve Announcement']);

            //Compensation
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Compensation']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Compensation']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Compensation']);

            // Compensation Adjustment
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Compensation Adjustment']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Compensation Adjustment']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Compensation Adjustment']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Compensation Adjustment']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Approve Compensation Adjustment']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Cancel Compensation Adjustment']);

            // Debt
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Debt']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Debt']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Debt']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Debt']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Settle Debt']);

            // Other
            $permissions[] = Permission::firstOrCreate(['name' => 'Convert To Debt']);

            //Finance Expense
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Expense']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Expense']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Expense']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Expense']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Approve Expense']);

            //Report
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Sale Report']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Return Report']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Expense Report']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Customer Report']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Customer Profile Report']);

            //Customer Profile Report
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Customer Profile Report']);

            // Contact
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Contact']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Contact']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Contact']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Contact']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Contact']);

            //Price Increment
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Price Increment']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Price Increment']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Price Increment']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Price Increment']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Approve Price Increment']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Price Increment']);

            //Supplier Profile Report
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Supplier Profile Report']);

            // Brand
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Brand']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Brand']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Brand']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Brand']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Import Brand']);

            //Payroll
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Payroll']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Payroll']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Payroll']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Payroll']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Approve Payroll']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Pay Payroll']);

            //Inventory Level Report
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Daily Inventory Report']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Inventory Transfer Report']);

            //Customer Deposit
            $permissions[] = Permission::firstOrCreate(['name' => 'Create Customer Deposit']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Read Customer Deposit']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Update Customer Deposit']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Delete Customer Deposit']);
            $permissions[] = Permission::firstOrCreate(['name' => 'Approve Customer Deposit']);

            // Delete Non-existent permissions
            Permission::whereNotIn('name', collect($permissions)->pluck('name'))->forceDelete();

            // Assign permissions to role
            $analyst->syncPermissions(Permission::where('name', 'like', 'Read%')->pluck('name'));

            $humanResourceManager->syncPermissions([
                'Create Department',
                'Create Employee',
                'Create Employee Transfer',
                'Create Warning',
                'Create Attendance',
                'Create Leave',
                'Create Expense Claim',
                'Create Advancement',
                'Create Announcement',
                'Create Compensation Adjustment',
                'Read Department',
                'Read Employee',
                'Read Employee Transfer',
                'Read Warning',
                'Read Attendance',
                'Read Leave',
                'Read Expense Claim',
                'Read Advancement',
                'Read Announcement',
                'Read Compensation Adjustment',
                'Update Department',
                'Update Employee',
                'Update Employee Transfer',
                'Update Warning',
                'Update Expense Claim',
                'Update Attendance',
                'Update Advancement',
                'Update Leave',
                'Update Announcement',
                'Update Compensation Adjustment',
                'Delete Department',
                'Delete Employee',
                'Delete Employee Transfer',
                'Delete Warning',
                'Delete Attendance',
                'Delete Leave',
                'Delete Expense Claim',
                'Delete Advancement',
                'Delete Announcement',
                'Delete Compensation Adjustment',
                'Import Employee',
                'Approve Attendance',
                'Approve Warning',
                'Approve Leave',
                'Approve Expense Claim',
                'Approve Advancement',
                'Approve Announcement',
                'Approve Compensation Adjustment',
                'Approve Employee Transfer',
                'Cancel Attendance',
                'Cancel Leave',
                'Cancel Compensation Adjustment',
                'Reject Expense Claim',
            ]);

            $humanResourceOfficer->syncPermissions([
                'Create Attendance',
                'Create Employee',
                'Create Employee Transfer',
                'Create Department',
                'Create Leave',
                'Create Expense Claim',
                'Create Advancement',
                'Create Warning',
                'Create Announcement',
                'Create Compensation Adjustment',
                'Read Attendance',
                'Read Employee',
                'Read Employee Transfer',
                'Read Department',
                'Read Leave',
                'Read Expense Claim',
                'Read Advancement',
                'Read Warning',
                'Read Announcement',
                'Read Compensation Adjustment',
                'Update Attendance',
                'Update Employee',
                'Update Employee Transfer',
                'Update Department',
                'Update Warning',
                'Update Leave',
                'Update Expense Claim',
                'Update Advancement',
                'Update Announcement',
                'Update Compensation Adjustment',
            ]);

            $purchaseManager->syncPermissions([
                'Create Purchase',
                'Create Supplier',
                'Create Contact',
                'Read Purchase',
                'Read Supplier',
                'Read Contact',
                'Read Product',
                'Read Brand',
                'Update Purchase',
                'Update Supplier',
                'Update Contact',
            ]);

            $salesOfficer->syncPermissions([
                'Create GDN',
                'Create Sale',
                'Create Proforma Invoice',
                'Create Customer',
                'Create Contact',
                'Create Return',
                'Create Reservation',
                'Read GDN',
                'Read Sale',
                'Read Proforma Invoice',
                'Read Customer',
                'Read Contact',
                'Read Return',
                'Read Product',
                'Read Brand',
                'Read Reservation',
                'Update GDN',
                'Update Sale',
                'Update Proforma Invoice',
                'Update Customer',
                'Update Contact',
                'Update Return',
                'Update Reservation',
                'Convert Proforma Invoice',
                'Convert Reservation',
            ]);

            $storeKeeper->syncPermissions([
                'Create GRN',
                'Create Transfer',
                'Create Damage',
                'Create Adjustment',
                'Create SIV',
                'Read GRN',
                'Read Job',
                'Read Available Inventory',
                'Read Reserved Inventory',
                'Read Work In Process Inventory',
                'Read On Hand Inventory',
                'Read Out Of Stock Inventory',
                'Read Expired Inventory',
                'Read Transfer',
                'Read Damage',
                'Read Adjustment',
                'Read Return',
                'Read SIV',
                'Read Product',
                'Read Brand',
                'Read Warehouse',
                'Read GDN',
                'Read Reservation',
                'Read BOM',
                'Update GRN',
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