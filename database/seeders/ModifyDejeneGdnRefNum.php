<?php

namespace Database\Seeders;

use App\Models\Gdn;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModifyDejeneGdnRefNum extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $this->gurdShola();

            $this->meri();

            $this->jacros();

            $this->signal();

            $this->urael();
        });
    }

    public function gurdShola()
    {
        $newRefNum = 145;

        $gdns = Gdn::query()
            ->where('company_id', 12)
            ->where('warehouse_id', 24)
            ->latest()
            ->get();

        foreach ($gdns as $gdn) {
            $newRefNum--;
            $gdn->code = '12_000000' . $newRefNum;
            $gdn->save();
        }
    }

    public function meri()
    {
        $newRefNum = 3573;

        $gdns = Gdn::query()
            ->where('company_id', 12)
            ->where('warehouse_id', 25)
            ->latest()
            ->get();

        foreach ($gdns as $gdn) {
            $newRefNum--;
            $gdn->code = '12_0000000' . $newRefNum;
            $gdn->save();
        }
    }

    public function jacros()
    {
        $newRefNum = 16504;

        $gdns = Gdn::query()
            ->where('company_id', 12)
            ->where('warehouse_id', 26)
            ->latest()
            ->get();

        foreach ($gdns as $gdn) {
            $newRefNum--;
            $gdn->code = '12_00000000' . $newRefNum;
            $gdn->save();
        }
    }

    public function signal()
    {
        $newRefNum = 26875;

        $gdns = Gdn::query()
            ->where('company_id', 12)
            ->where('warehouse_id', 27)
            ->latest()
            ->get();

        foreach ($gdns as $gdn) {
            $newRefNum--;
            $gdn->code = '12_000000000' . $newRefNum;
            $gdn->save();
        }
    }

    public function urael()
    {
        $newRefNum = 26876;

        $gdns = Gdn::query()
            ->where('company_id', 12)
            ->where('warehouse_id', 28)
            ->latest()
            ->get();

        foreach ($gdns as $gdn) {
            $newRefNum--;
            $gdn->code = '12_0000000000' . $newRefNum;
            $gdn->save();
        }
    }
}
