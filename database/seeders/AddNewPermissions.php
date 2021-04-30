<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AddNewPermissions extends Seeder
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
            Permission::create(['name' => 'Delete Approved GDN']);
            Permission::create(['name' => 'Delete Approved GRN']);
            Permission::create(['name' => 'Delete Approved PO']);
            Permission::create(['name' => 'Delete Approved Purchase']);
            Permission::create(['name' => 'Delete Approved Sale']);
            Permission::create(['name' => 'Delete Approved Transfer']);

            $systemManager = Role::findByName('System Manager');
            $systemManager->syncPermissions(Permission::all());
        });
    }
}
