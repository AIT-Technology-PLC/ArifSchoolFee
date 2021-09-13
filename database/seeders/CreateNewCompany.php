<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Plan;
use App\Models\Warehouse;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateNewCompany extends Seeder
{
    public function run(Faker $faker)
    {
        DB::transaction(function () use ($faker) {
            $company = Company::create([
                'name' => $faker->company,
                'currency' => 'ETB',
                'enabled' => 1,
                'plan_id' => Plan::where('name', 'professional')->first()->id,
            ]);

            $user = User::create([
                'name' => 'Abebe Kebede',
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
            ]);

            Employee::create([
                'user_id' => $user->id,
                'company_id' => $company->id,
                'enabled' => 1,
                'position' => 'Onrica Support Department',
            ]);

            $warehouse = Warehouse::create([
                'company_id' => $company->id,
                'name' => 'Main Warehouse',
                'location' => 'Unknown',
                'is_sales_store' => 0,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            $user->assignRole('System Manager');

            $user->warehouse()->associate($warehouse)->save();
        });
    }
}
