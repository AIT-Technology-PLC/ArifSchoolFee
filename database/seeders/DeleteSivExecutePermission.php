<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DeleteSivExecutePermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $systemManager = Role::findByName('System Manager');

        $systemManager->revokePermissionTo('Execute SIV');

        Permission::findByName('Execute SIV')->delete();
    }
}
