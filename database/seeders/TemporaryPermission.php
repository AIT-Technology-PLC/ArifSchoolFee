<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class TemporaryPermission extends Seeder
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
            Permission::firstOrCreate(['name' => 'Read Available Inventory']);
            Permission::firstOrCreate(['name' => 'Read Reserved Inventory']);
            Permission::firstOrCreate(['name' => 'Read Work In Process Inventory']);
            Permission::firstOrCreate(['name' => 'Read On Hand Inventory']);
            Permission::firstOrCreate(['name' => 'Read Out Of Stock Inventory']);

            User::query()
                ->permission('Read Merchandise')
                ->get()
                ->each
                ->givePermissionTo([
                    'Read Available Inventory',
                    'Read Reserved Inventory',
                    'Read Work In Process Inventory',
                    'Read On Hand Inventory',
                    'Read Out Of Stock Inventory',
                ]);
        });
    }
}
