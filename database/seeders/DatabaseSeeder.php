<?php

namespace Database\Seeders;

use App\Actions\CreateSchoolAction;
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
            CreateSchoolAction::class,
            CreateAdminUser::class,
        ]);
    }
}
