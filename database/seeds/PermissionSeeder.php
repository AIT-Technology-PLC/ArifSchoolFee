<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            // Roles
            $systemManager = Role::create(['name' => 'System Manager']);
            $storeKeeper = Role::create(['name' => 'Store Keeper']);
            $salesOfficer = Role::create(['name' => 'Sales Officer']);
            $salesManager = Role::create(['name' => 'Sales Manager']);
            $purchaseManager = Role::create(['name' => 'Purchase Manager']);
            $analyst = Role::create(['name' => 'Analyst']);

            // GDN
            $createGdn = Permission::create(['name' => 'Create GDN']);
            $createGdn->assignRole($storeKeeper);
            $createGdn->assignRole($salesOfficer);
            $createGdn->assignRole($salesManager);

            $readGdn = Permission::create(['name' => 'Read GDN']);
            $readGdn->assignRole($storeKeeper);
            $readGdn->assignRole($salesOfficer);
            $readGdn->assignRole($salesManager);
            $readGdn->assignRole($analyst);

            $updateGdn = Permission::create(['name' => 'Update GDN']);
            $updateGdn->assignRole($storeKeeper);
            $updateGdn->assignRole($salesOfficer);
            $updateGdn->assignRole($salesManager);

            $deleteGdn = Permission::create(['name' => 'Delete GDN']);

            $approveGdn = Permission::create(['name' => 'Approve GDN']);
            $approveGdn->assignRole($salesManager);

            $subtractGdn = Permission::create(['name' => 'Subtract GDN']);
            $subtractGdn->assignRole($salesManager);
            $subtractGdn->assignRole($storeKeeper);

            // GRN
            $createGrn = Permission::create(['name' => 'Create GRN']);
            $createGrn->assignRole($storeKeeper);
            $createGrn->assignRole($purchaseManager);

            $readGrn = Permission::create(['name' => 'Read GRN']);
            $readGrn->assignRole($storeKeeper);
            $readGrn->assignRole($purchaseManager);
            $readGrn->assignRole($analyst);

            $updateGrn = Permission::create(['name' => 'Update GRN']);
            $updateGrn->assignRole($storeKeeper);
            $updateGrn->assignRole($purchaseManager);

            $deleteGrn = Permission::create(['name' => 'Delete GRN']);

            $approveGrn = Permission::create(['name' => 'Approve GRN']);
            $approveGrn->assignRole($purchaseManager);

            $addGrn = Permission::create(['name' => 'Add GRN']);
            $addGrn->assignRole($purchaseManager);
            $addGrn->assignRole($storeKeeper);

            // Transfer
            $createTransfer = Permission::create(['name' => 'Create Transfer']);
            $createTransfer->assignRole($storeKeeper);

            $readTransfer = Permission::create(['name' => 'Read Transfer']);
            $readTransfer->assignRole($storeKeeper);

            $updateTransfer = Permission::create(['name' => 'Update Transfer']);
            $updateTransfer->assignRole($storeKeeper);

            $deleteTransfer = Permission::create(['name' => 'Delete Transfer']);

            $approveTransfer = Permission::create(['name' => 'Approve Transfer']);
            $approveTransfer->assignRole($storeKeeper);

            $makeTransfer = Permission::create(['name' => 'Make Transfer']);
            $makeTransfer->assignRole($storeKeeper);

            // Sale
            $createSale = Permission::create(['name' => 'Create Sale']);
            $createSale->assignRole($salesOfficer);
            $createSale->assignRole($salesManager);

            $readSale = Permission::create(['name' => 'Read Sale']);
            $readSale->assignRole($salesOfficer);
            $readSale->assignRole($salesManager);
            $readSale->assignRole($analyst);

            $updateSale = Permission::create(['name' => 'Update Sale']);
            $updateSale->assignRole($salesOfficer);
            $updateSale->assignRole($salesManager);

            $deleteSale = Permission::create(['name' => 'Delete Sale']);

            $approveSale = Permission::create(['name' => 'Approve Sale']);
            $approveSale->assignRole($salesManager);

            // Purchase
            $createPurchase = Permission::create(['name' => 'Create Purchase']);
            $createPurchase->assignRole($purchaseManager);

            $readPurchase = Permission::create(['name' => 'Read Purchase']);
            $readPurchase->assignRole($purchaseManager);
            $createPurchase->assignRole($analyst);

            $updatePurchase = Permission::create(['name' => 'Update Purchase']);
            $updatePurchase->assignRole($purchaseManager);

            $deletePurchase = Permission::create(['name' => 'Delete Purchase']);

            $approvePurchase = Permission::create(['name' => 'Approve Purchase']);
            $approvePurchase->assignRole($purchaseManager);

            // PO
            $createPo = Permission::create(['name' => 'Create PO']);
            $createPo->assignRole($salesOfficer);
            $createPo->assignRole($salesManager);

            $readPo = Permission::create(['name' => 'Read PO']);
            $readPo->assignRole($salesOfficer);
            $readPo->assignRole($salesManager);
            $readPo->assignRole($analyst);

            $updatePo = Permission::create(['name' => 'Update PO']);
            $updatePo->assignRole($salesOfficer);
            $updatePo->assignRole($salesManager);

            $deletePo = Permission::create(['name' => 'Delete PO']);

            $approvePo = Permission::create(['name' => 'Approve PO']);
            $approvePo->assignRole($salesOfficer);
            $approvePo->assignRole($salesManager);

            // Product
            $createProduct = Permission::create(['name' => 'Create Product']);
            $createProduct->assignRole($purchaseManager);
            $createProduct->assignRole($salesManager);

            $readProduct = Permission::create(['name' => 'Read Product']);

            $updateProduct = Permission::create(['name' => 'Update Product']);
            $updateProduct->assignRole($purchaseManager);
            $updateProduct->assignRole($salesManager);

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

            $systemManager->givePermissionTo(Permission::all());
        });
    }
}
