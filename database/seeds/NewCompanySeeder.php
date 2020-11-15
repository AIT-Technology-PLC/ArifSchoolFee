<?php

use App\Models\Company;
use App\Models\Employee;
use App\Models\Permission;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewCompanySeeder extends Seeder
{
    public function run(Faker $faker)
    {
        for ($i=1;$i<=3;$i++) {
            DB::transaction(function () use ($faker) {
                $company = Company::create([
                    'name' => $faker->company,
                    'currency' => 'ETB',
                    'enabled' => 0,
                ]);

                $user = User::create([
                    'name' => $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                ]);

                $permission = Permission::create([
                    'settings' => 'crud',
                    'warehouses' => 'crud',
                    'products' => 'crud',
                    'merchandises' => 'crud',
                    'manufacturings' => 'crud',
                ]);

                Employee::create([
                    'user_id' => $user->id,
                    'company_id' => $company->id,
                    'permission_id' => $permission->id,
                    'enabled' => 1,
                    'position' => 'Admin',
                ]);
            });
        }
    }
}
