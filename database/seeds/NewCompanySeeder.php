<?php

use App\Models\Company;
use App\Models\Employee;
use App\Models\Warehouse;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewCompanySeeder extends Seeder
{
    public function run(Faker $faker)
    {
        for ($i = 0; $i <= 2; $i++) {
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

                Employee::create([
                    'user_id' => $user->id,
                    'company_id' => $company->id,
                    'enabled' => 1,
                    'position' => 'Manager',
                ]);

                Warehouse::create([
                    'company_id' => $company->id,
                    'name' => 'Primary',
                    'location' => 'Unknown',
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]);

                $user->assignRole('System Manager');
            });
        }
    }
}
