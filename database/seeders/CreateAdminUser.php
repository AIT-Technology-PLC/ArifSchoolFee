<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class CreateAdminUser extends Seeder
{
    public function run(Generator $generator): void
    {
        $user = User::create([
            'name' => $generator->name,
            'email' => User::where('email', 'admin@ait-tech.com')->exists() ? $generator->unique()->safeEmail : 'admin@ait-tech.com',
            'password' => 'adminpassword',
            'is_admin' => 1,
        ]);

        $user->syncPermissions(
            Permission::where('name', 'LIKE', 'Manage Admin Panel%')->get()
        );

        $this->command->table(['email', 'password'], [[$user->email, 'adminpassword']]);
    }
}
