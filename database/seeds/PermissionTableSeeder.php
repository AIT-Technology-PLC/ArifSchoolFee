<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = new Permission();

        $permission->create([
            'role' => 'SuperAdmin',
            'description' => 'Has full access to the whole system',
        ]);

        $permission->create([
            'role' => 'Admin',
            'description' => 'Has full access to the whole system including adding new employee and excluding settings',
        ]);

        $permission->create([
            'role' => 'Analyst',
            'description' => 'Has read access to the whole system excluding settings',
        ]);

        $permission->create([
            'role' => 'Operatives',
            'description' => 'Has create, read, and update access to the whole system excluding settings',
        ]);
    }
}
