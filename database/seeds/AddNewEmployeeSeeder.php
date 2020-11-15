<?php

use App\Models\Employee;
use App\Models\Permission;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddNewEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $companiesId = DB::table('companies')->get(['id'])->map(fn($company) => $company->id);

        for ($i = 0; $i <= 2; $i++) {
            DB::transaction(function () use ($faker, $companiesId) {
                $user = User::create([
                    'name' => $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                ]);

                $permission = Permission::create([
                    'settings' => '',
                    'warehouses' => 'cr',
                    'products' => 'crud',
                    'merchandises' => 'crud',
                    'manufacturings' => 'crud',
                ]);

                $companyId = $faker->randomElement($companiesId);

                Employee::create([
                    'user_id' => $user->id,
                    'company_id' => $companyId,
                    'permission_id' => $permission->id,
                    'created_by' => $companyId,
                    'updated_by' => $companyId,
                    'position' => $faker->jobTitle,
                    'enabled' => $faker->randomElement([0, 1]),
                ]);
            });
        }
    }
}
