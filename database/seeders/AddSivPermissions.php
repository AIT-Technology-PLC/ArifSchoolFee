<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AddSivPermissions extends Seeder
{
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        DB::transaction(function () {
            $systemManager = Role::where('name', 'System Manager')->first();
            $analyst = Role::where('name', 'Analyst')->first();

            DB::table('permissions')->insert([
                [
                    'name' => 'Create SIV',
                    'guard_name' => 'web',
                ],
                [
                    'name' => 'Read SIV',
                    'guard_name' => 'web',
                ],
                [
                    'name' => 'Update SIV',
                    'guard_name' => 'web',
                ],
                [
                    'name' => 'Delete SIV',
                    'guard_name' => 'web',
                ],
                [
                    'name' => 'Approve SIV',
                    'guard_name' => 'web',
                ],
                [
                    'name' => 'Execute SIV',
                    'guard_name' => 'web',
                ],
                [
                    'name' => 'Delete Approved SIV',
                    'guard_name' => 'web',
                ],
            ]);

            $systemManager->syncPermissions(Permission::all());
            
            $analyst->givePermissionTo('Read SIV');
        });
    }
}
