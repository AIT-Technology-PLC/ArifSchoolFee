<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AddNewEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $systemManagerUser = User::role('System Manager')
            ->get()
            ->random(1)
            ->first();

        $employee = Employee::where('user_id', $systemManagerUser->id)->first();

        DB::transaction(function () use ($faker, $employee) {
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
            ]);

            Employee::create([
                'user_id' => $user->id,
                'company_id' => $employee->company_id,
                'created_by' => $employee->user_id,
                'updated_by' => $employee->user_id,
                'position' => $faker->jobTitle,
                'enabled' => 1,
            ]);

            $user->assignRole(
                Role::where('name', '<>', 'System Manager')
                    ->get()
                    ->random(1)
            );
        });

    }
}
