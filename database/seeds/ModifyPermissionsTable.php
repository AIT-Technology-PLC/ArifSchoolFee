<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class ModifyPermissionsTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        DB::transaction(function () {
            // Revoke Permission from all roles except Analyst
            $storeKeeper = Role::findByName('Store Keeper');
            $storeKeeper->revokePermissionTo($storeKeeper->permissions);

            $salesOfficer = Role::findByName('Sales Officer');
            $salesOfficer->revokePermissionTo($salesOfficer->permissions);

            $salesManager = Role::findByName('Sales Manager');
            $salesManager->revokePermissionTo($salesManager->permissions);

            $purchaseManager = Role::findByName('Purchase Manager');
            $purchaseManager->revokePermissionTo($purchaseManager->permissions);

            $systemManager = Role::findByName('System Manager');
            $systemManager->revokePermissionTo($systemManager->permissions);

            // New Roles
            $tender = Role::create(['name' => 'Tender']);
            $userManager = Role::create(['name' => 'User Manager']);

            // New Permissions
            Permission::create(['name' => 'Create Supplier']);
            Permission::create(['name' => 'Read Supplier']);
            Permission::create(['name' => 'Update Supplier']);
            Permission::create(['name' => 'Delete Supplier']);

            Permission::create(['name' => 'Create Customer']);
            Permission::create(['name' => 'Read Customer']);
            Permission::create(['name' => 'Update Customer']);
            Permission::create(['name' => 'Delete Customer']);

            Permission::create(['name' => 'Create Tender']);
            Permission::create(['name' => 'Read Tender']);
            Permission::create(['name' => 'Update Tender']);
            Permission::create(['name' => 'Delete Tender']);

            Permission::create(['name' => 'Create Prices']);
            Permission::create(['name' => 'Read Prices']);
            Permission::create(['name' => 'Update Prices']);
            Permission::create(['name' => 'Delete Prices']);

            Permission::create(['name' => 'Update Company']);

            // Assign Permissions to Roles
            $storeKeeper->syncPermissions(['Create Merchandise', 'Read Merchandise', 'Update Merchandise', 'Subtract GDN', 'Create GRN',
                'Read GRN', 'Update GRN', 'Add GRN', 'Create Transfer', 'Read Transfer', 'Update Transfer', 'Make Transfer', 'Read Product', 'Read Warehouse']);

            $salesOfficer->syncPermissions(['Create GDN', 'Read GDN', 'Update GDN', 'Create Sale', 'Read Sale', 'Update Sale', 'Create Customer',
                'Read Customer', 'Update Customer', 'Create PO', 'Read PO', 'Update PO', 'Read Product']);

            $purchaseManager->syncPermissions(['Create Purchase', 'Read Purchase', 'Update Purchase', 'Create Supplier', 'Read Supplier',
                'Update Supplier', 'Read Product']);

            $tender->syncPermissions(['Create Tender', 'Read Tender', 'Update Tender']);

            $userManager->syncPermissions(['Create Employee', 'Read Employee', 'Update Employee']);

            $systemManager->givePermissionTo(Permission::all());
        });
    }
}
