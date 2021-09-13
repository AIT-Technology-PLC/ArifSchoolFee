<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class RemovePriceRelatedData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            Feature::firstWhere('name', 'Price Management')->forceDelete();

            Permission::whereIn('name', ['Create Price', 'Read Price', 'Update Price', 'Delete Price'])->forceDelete();
        });
    }
}
