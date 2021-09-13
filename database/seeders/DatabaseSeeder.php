<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            Plans::class,
            Limits::class,
            Features::class,
            Permissions::class,
            CreateNewCompany::class,
        ]);
    }
}
