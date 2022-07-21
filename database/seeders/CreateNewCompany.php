<?php

namespace Database\Seeders;

use App\Actions\CreateUserAction;
use App\Models\Company;
use App\Models\Plan;
use App\Models\User;
use App\Models\Warehouse;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateNewCompany extends Seeder
{
    public function run(Faker $faker, CreateUserAction $action)
    {
        DB::transaction(function () use ($faker, $action) {
            $company = Company::create([
                'name' => $faker->company,
                'currency' => 'ETB',
                'enabled' => 1,
                'plan_id' => Plan::firstWhere('name', 'professional')->id,
            ]);

            $warehouse = Warehouse::create([
                'company_id' => $company->id,
                'name' => 'Main Warehouse',
                'location' => 'Unknown',
                'is_sales_store' => 0,
                'is_active' => 1,
            ]);

            $user = $action->execute([
                'name' => $faker->name,
                'email' => User::count() ? $faker->unique()->safeEmail : 'admin@onrica.com',
                'password' => 'password',
                'warehouse_id' => $warehouse->id,
                'enabled' => 1,
                'position' => 'Onrica Support Department',
                'role' => 'System Manager',
                'gender' => 'male',
                'address' => 'Bole',
                'job_type' => 'full time',
                'phone' => '0312131415',
            ]);

            $user->employee->company()->associate($company)->save();

            $warehouse->createdBy()->associate($user)->save();

            $warehouse->updatedBy()->associate($user)->save();
        });
    }
}
