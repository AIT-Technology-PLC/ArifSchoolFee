<?php

namespace Database\Seeders;

use App\Models\Limit;
use Illuminate\Database\Seeder;

class LimitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Limit::firstOrCreate(['name' => 'warehouse']);
    }
}
