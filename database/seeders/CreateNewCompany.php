<?php

namespace Database\Seeders;

use App\Actions\CreateCompanyAction;
use App\Models\Plan;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateNewCompany extends Seeder
{
    public function run(Faker $faker, CreateCompanyAction $action)
    {
        $user = DB::transaction(function () use ($faker, $action) {
            return $action->execute([
                'company_name' => $faker->company,
                'name' => $faker->name,
                'email' => User::count() ? $faker->unique()->safeEmail : 'user@onrica.com',
                'password' => 'userpassword',
                'subscriptions' => [
                    'plan_id' => Plan::firstWhere('name', 'v3-professional')->id,
                    'months' => 12,
                ],
            ]);
        });

        $this->command->table(['email', 'password'], [[$user->email, 'userpassword']]);
    }
}
