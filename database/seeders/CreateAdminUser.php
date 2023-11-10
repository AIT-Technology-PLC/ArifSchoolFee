<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Generator;
use Illuminate\Database\Seeder;

class CreateAdminUser extends Seeder
{
    public function run(Generator $generator): void
    {
        $user = User::create([
            'name' => $generator->name,
            'email' => User::where('email', 'admin@onrica.com')->exists() ? $generator->unique()->safeEmail : 'admin@onrica.com',
            'password' => 'adminpassword',
            'is_admin' => 1,
        ]);

        $this->command->table(['email', 'password'], [[$user->email, 'adminpassword']]);
    }
}
