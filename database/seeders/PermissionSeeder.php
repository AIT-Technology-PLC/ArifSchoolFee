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
            $createGdn = Permission::create(['name' => 'Create GDN']);
            $readGdn = Permission::create(['name' => 'Read GDN']);
            $updateGdn = Permission::create(['name' => 'Update GDN']);
            $deleteGdn = Permission::create(['name' => 'Delete GDN']);
            $approveGdn = Permission::create(['name' => 'Approve GDN']);
            $subtractGdn = Permission::create(['name' => 'Subtract GDN']);
            $subtractGdn = Permission::create(['name' => 'Delete Approved GDN']);

            // GRN
            $createGrn = Permission::create(['name' => 'Create GRN']);
            $readGrn = Permission::create(['name' => 'Read GRN']);
            $updateGrn = Permission::create(['name' => 'Update GRN']);
            $deleteGrn = Permission::create(['name' => 'Delete GRN']);
            $approveGrn = Permission::create(['name' => 'Approve GRN']);
            $addGrn = Permission::create(['name' => 'Add GRN']);
            $addGrn = Permission::create(['name' => 'Delete Approved GRN']);

            // Transfer
            $createTransfer = Permission::create(['name' => 'Create Transfer']);
            $readTransfer = Permission::create(['name' => 'Read Transfer']);
            $updateTransfer = Permission::create(['name' => 'Update Transfer']);
            $deleteTransfer = Permission::create(['name' => 'Delete Transfer']);
            $approveTransfer = Permission::create(['name' => 'Approve Transfer']);
            $makeTransfer = Permission::create(['name' => 'Make Transfer']);
            $makeTransfer = Permission::create(['name' => 'Delete Approved Transfer']);

            // Merchandise
            $createMerchandise = Permission::create(['name' => 'Create Merchandise']);
            $readMerchandise = Permission::create(['name' => 'Read Merchandise']);
            $updateMerchandise = Permission::create(['name' => 'Update Merchandise']);
            $deleteMerchandise = Permission::create(['name' => 'Delete Merchandise']);

            // Sale
            $createSale = Permission::create(['name' => 'Create Sale']);
            $readSale = Permission::create(['name' => 'Read Sale']);
            $updateSale = Permission::create(['name' => 'Update Sale']);
            $deleteSale = Permission::create(['name' => 'Delete Sale']);
            $approveSale = Permission::create(['name' => 'Approve Sale']);
            $approveSale = Permission::create(['name' => 'Delete Approved Sale']);

            // Purchase
            $createPurchase = Permission::create(['name' => 'Create Purchase']);
            $readPurchase = Permission::create(['name' => 'Read Purchase']);
            $updatePurchase = Permission::create(['name' => 'Update Purchase']);
            $deletePurchase = Permission::create(['name' => 'Delete Purchase']);
            $approvePurchase = Permission::create(['name' => 'Approve Purchase']);
            $approvePurchase = Permission::create(['name' => 'Delete Approved Purchase']);

            // PO
            $createPo = Permission::create(['name' => 'Create PO']);
            $readPo = Permission::create(['name' => 'Read PO']);
            $updatePo = Permission::create(['name' => 'Update PO']);
            $deletePo = Permission::create(['name' => 'Delete PO']);
            $approvePo = Permission::create(['name' => 'Approve PO']);
            $approvePo = Permission::create(['name' => 'Delete Approved PO']);

            // Product
            $createProduct = Permission::create(['name' => 'Create Product']);
            $readProduct = Permission::create(['name' => 'Read Product']);
            $updateProduct = Permission::create(['name' => 'Update Product']);
            $deleteProduct = Permission::create(['name' => 'Delete Product']);

            // Warehouse
            $createWarehouse = Permission::create(['name' => 'Create Warehouse']);
            $readWarehouse = Permission::create(['name' => 'Read Warehouse']);
            $updateWarehouse = Permission::create(['name' => 'Update Warehouse']);
            $deleteWarehouse = Permission::create(['name' => 'Delete Warehouse']);

            // Employee
            $createEmployee = Permission::create(['name' => 'Create Employee']);
            $readEmployee = Permission::create(['name' => 'Read Employee']);
            $updateEmployee = Permission::create(['name' => 'Update Employee']);
            $deleteEmployee = Permission::create(['name' => 'Delete Employee']);

            // Supplier
            $createSupplier = Permission::create(['name' => 'Create Supplier']);
            $readSupplier = Permission::create(['name' => 'Read Supplier']);
            $updateSupplier = Permission::create(['name' => 'Update Supplier']);
            $deleteSupplier = Permission::create(['name' => 'Delete Supplier']);

            // Customer
            $createCustomer = Permission::create(['name' => 'Create Customer']);
            $readCustomer = Permission::create(['name' => 'Read Customer']);
            $updateCustomer = Permission::create(['name' => 'Update Customer']);
            $deleteCustomer = Permission::create(['name' => 'Delete Customer']);

            // Tender
            $createTender = Permission::create(['name' => 'Create Tender']);
            $readTender = Permission::create(['name' => 'Read Tender']);
            $updateTender = Permission::create(['name' => 'Update Tender']);
            $deleteTender = Permission::create(['name' => 'Delete Tender']);

            // Price
            $createPrice = Permission::create(['name' => 'Create Price']);
            $readPrice = Permission::create(['name' => 'Read Price']);
            $updatePrice = Permission::create(['name' => 'Update Price']);
            $deletePrice = Permission::create(['name' => 'Delete Price']);

            // Company
            $updateCompany = Permission::create(['name' => 'Update Company']);

            // Assign permissions to role
            $analyst->syncPermissions([
                'Read GDN',
                'Read GRN',
                'Read PO',
                'Read Purchase',
                'Read Sale',
            ]);

            $purchaseManager->syncPermissions([
                'Create Purchase',
                'Create Supplier',
                'Read Product',
                'Read Purchase',
                'Read Supplier',
                'Update Purchase',
                'Update Supplier',
            ]);

            $salesOfficer->syncPermissions([
                'Create Customer',
                'Create GDN',
                'Create PO',
                'Create Sale',
                'Read Customer',
                'Read GDN',
                'Read PO',
                'Read Product',
                'Read Sale',
                'Update Customer',
                'Update GDN',
                'Update PO',
                'Update Sale',
            ]);

            $storeKeeper->syncPermissions([
                'Add GRN',
                'Create GRN',
                'Create Merchandise',
                'Create Transfer',
                'Make Transfer',
                'Read GRN',
                'Read Merchandise',
                'Read Product',
                'Read Transfer',
                'Read Warehouse',
                'Subtract GDN',
                'Update GRN',
                'Update Merchandise',
                'Update Transfer',
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
