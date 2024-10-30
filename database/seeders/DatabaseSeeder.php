<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            Plans::class,
            Limits::class,
            Features::class,
            Permissions::class,
            CreateNewSchool::class,
            CreateAdminUser::class,
        ]);
    }
}
