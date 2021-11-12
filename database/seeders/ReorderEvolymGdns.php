<?php

namespace Database\Seeders;

use App\Models\Gdn;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReorderEvolymGdns extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            // 1st Round
            $newRefNum = 1800;

            $gdns = Gdn::query()
                ->where('company_id', 15)
                ->where('warehouse_id', 38)
                ->latest('issued_on')
                ->get();

            foreach ($gdns as $gdn) {
                $newRefNum--;
                $gdn->code = $newRefNum;
                $gdn->save();
            }

            // 2nd Round
            $newRefNum = 180;

            $gdns = Gdn::query()
                ->where('company_id', 15)
                ->where('warehouse_id', 38)
                ->latest('issued_on')
                ->get();

            foreach ($gdns as $gdn) {
                $newRefNum--;
                $gdn->code = $newRefNum;
                $gdn->save();
            }
        });
    }
}
