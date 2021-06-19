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
            PlanTableSeeder::class,
            LimitTableSeeder::class,
            FeatureTableSeeder::class,
            PermissionSeeder::class,
            NewCompanySeeder::class,
        ]);
    }
}
