<?php

namespace Database\Seeders;

use App\Models\Integration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Integrations extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            Integration::updateOrCreate(
                ['name' => 'Point of Sale'],
                ['is_enabled' => 1]
            );
        });
    }
}
