<?php

use App\Models\Grn;
use App\Models\InventoryHistory;
use App\Models\Merchandise;
use App\Models\Reservation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {

            // Assign company to reservation with none
            foreach (InventoryHistory::whereNull('company_id')->get() as $inventoryHistory) {
                $inventoryHistory->company_id = $inventoryHistory->warehouse->company_id;
                $inventoryHistory->save();
            }

            InventoryHistory::where('model_type', 'App\Models\Gdn')->where('model_id', 26704)->orderBy('id', 'ASC')->limit(50)->forceDelete();
            InventoryHistory::where('model_type', 'App\Models\Gdn')->where('model_id', 26118)->orderBy('id', 'ASC')->limit(8)->forceDelete();
            InventoryHistory::where('model_type', 'App\Models\Gdn')->where('model_id', 24837)->orderBy('id', 'ASC')->limit(18)->forceDelete();

            // Fix Reservation <-> Gdn double subtract issue
            $convertedReservations = Reservation::converted()->notCancelled()->whereHas('reservationDetails')->get();

            foreach ($convertedReservations as $reservation) {
                $existsByReservation = InventoryHistory::where('model_type', 'App\Models\Reservation')->where('model_id', $reservation->id)->exists();
                $existsByGdn = InventoryHistory::where('model_type', 'App\Models\Gdn')->where('model_id', $reservation->reservable_id)->exists();

                if ($existsByReservation && $existsByGdn) {
                    InventoryHistory::where('model_type', 'App\Models\Gdn')->where('model_id', $reservation->reservable_id)->forceDelete();
                }
            }

            // Remove JSP's
            InventoryHistory::where('company_id', 42)->forceDelete();
            Merchandise::where('company_id', 42)->forceDelete();

            foreach (Grn::where('company_id', 42)->get() as $grn) {
                $grn->added_by = null;
                $grn->approved_by = null;

                $grn->save();
            }

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
