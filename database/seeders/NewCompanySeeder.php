<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Warehouse;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NewCompanySeeder extends Seeder
{
    public function run(Faker $faker)
    {
        DB::transaction(function () use ($faker) {
            $company = Company::create([
                'name' => $faker->company,
                'currency' => 'ETB',
                'enabled' => 1,
                'membership_plan' => 'Professional',
                'plan_id' => Plan::where('name', 'professional')->first()->id,
            ]);

            $user = User::create([
                'name' => 'Abebe Kebede',
                'email' => 'abebe@onrica.com',
                'password' => Hash::make('password'),
            ]);

            Employee::create([
                'user_id' => $user->id,
                'company_id' => $company->id,
                'enabled' => 1,
                'position' => 'General Manager',
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
