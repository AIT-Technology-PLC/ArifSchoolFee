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
        $newRefNum = 3316;

        $gdns = Gdn::query()
            ->where('company_id', 12)
            ->where('warehouse_id', 24)
            ->whereDate('created_at', '<=', '2021-10-10')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($gdns as $gdn) {
            $newRefNum--;
            $gdn->code = '12_000000' . $newRefNum;
            $gdn->save();
        }
    }

    public function meri()
    {
        $newRefNum = 3332;

        $gdns = Gdn::query()
            ->where('company_id', 12)
            ->where('warehouse_id', 25)
            ->whereDate('created_at', '<=', '2021-10-10')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($gdns as $gdn) {
            $newRefNum--;
            $gdn->code = '12_0000000' . $newRefNum;
            $gdn->save();
        }
    }

    public function jacros()
    {
        $newRefNum = 16436;

        $gdns = Gdn::query()
            ->where('company_id', 12)
            ->where('warehouse_id', 26)
            ->whereDate('created_at', '<=', '2021-10-10')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($gdns as $gdn) {
            $newRefNum--;
            $gdn->code = '12_00000000' . $newRefNum;
            $gdn->save();
        }
    }

    public function signal()
    {
        $newRefNum = 2755;

        $gdns = Gdn::query()
            ->where('company_id', 12)
            ->where('warehouse_id', 27)
            ->whereDate('created_at', '<=', '2021-10-10')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($gdns as $gdn) {
            $newRefNum--;
            $gdn->code = '12_000000000' . $newRefNum;
            $gdn->save();
        }
    }

    public function urael()
    {
        $newRefNum = 17648;

        $gdns = Gdn::query()
            ->where('company_id', 12)
            ->where('warehouse_id', 28)
            ->whereDate('created_at', '<=', '2021-10-10')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($gdns as $gdn) {
            $newRefNum--;
            $gdn->code = '12_0000000000' . $newRefNum;
            $gdn->save();
        }
    }
}
