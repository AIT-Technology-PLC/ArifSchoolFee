<?php

use App\Models\AdjustmentDetail;
use App\Models\DamageDetail;
use App\Models\GdnDetail;
use App\Models\GrnDetail;
use App\Models\InventoryHistory;
use App\Models\JobDetailHistory;
use App\Models\JobExtra;
use App\Models\ReservationDetail;
use App\Models\ReturnDetail;
use App\Models\TransferDetail;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $grnDetails = (new GrnDetail)->query()
            ->whereHas('grn', function ($q) {
                return $q->added();
            })
            ->join('grns', 'grn_details.grn_id', '=', 'grns.id')
            ->select(['grn_details.warehouse_id', 'product_id', 'quantity', 'issued_on', 'grns.id AS model_id', 'grns.created_at AS created', 'grns.updated_at AS updated'])
            ->get();

        data_set($grnDetails, '*.model_type', 'App\Models\Grn');
        data_set($grnDetails, '*.is_subtract', '0');

        InventoryHistory::insert($grnDetails->toArray());

        //Gdn
        $gdnDetails = (new GdnDetail)->query()
            ->whereHas('gdn', function ($q) {
                return $q->subtracted();
            })
            ->join('gdns', 'gdn_details.gdn_id', '=', 'gdns.id')
            ->get(['gdn_details.warehouse_id', 'product_id', 'quantity', 'issued_on', 'gdn_id AS model_id', 'gdn_details.created_at AS created', 'gdn_details.updated_at AS updated']);

        data_set($gdnDetails, '*.model_type', 'App\Models\Gdn');
        data_set($gdnDetails, '*.is_subtract', '1');

        InventoryHistory::insert($gdnDetails->toArray());

        //Transfer Subtracted
        $transferDetails = (new TransferDetail)->query()
            ->whereHas('transfer', function ($q) {
                return $q->subtracted();
            })
            ->join('transfers', 'transfer_details.transfer_id', '=', 'transfers.id')
            ->get(['transfers.transferred_from AS warehouse_id', 'product_id', 'quantity', 'issued_on', 'transfer_id AS model_id', 'transfer_details.created_at AS created', 'transfer_details.updated_at AS updated']);

        data_set($transferDetails, '*.model_type', 'App\Models\Transfer');
        data_set($transferDetails, '*.is_subtract', '1');

        InventoryHistory::insert($transferDetails->toArray());

        //Transfer Add
        $transferDetails = (new TransferDetail)->query()
            ->whereHas('transfer', function ($q) {
                return $q->added();
            })
            ->join('transfers', 'transfer_details.transfer_id', '=', 'transfers.id')
            ->get(['transfers.transferred_to AS warehouse_id', 'product_id', 'quantity', 'issued_on', 'transfer_id AS model_id', 'transfer_details.created_at AS created', 'transfer_details.updated_at AS updated']);

        data_set($transferDetails, '*.model_type', 'App\Models\Transfer');
        data_set($transferDetails, '*.is_subtract', '0');

        InventoryHistory::insert($transferDetails->toArray());

        //Damage
        $damageDetails = (new DamageDetail)->query()
            ->whereHas('damage', function ($q) {
                return $q->subtracted();
            })
            ->join('damages', 'damage_details.damage_id', '=', 'damages.id')
            ->get(['damage_details.warehouse_id', 'product_id', 'quantity', 'issued_on', 'damage_id AS model_id', 'damage_details.created_at AS created', 'damage_details.updated_at AS updated']);

        data_set($damageDetails, '*.model_type', 'App\Models\Damage');
        data_set($damageDetails, '*.is_subtract', '1');

        InventoryHistory::insert($damageDetails->toArray());

        //Return
        $returnDetails = (new ReturnDetail)->query()
            ->whereHas('returnn', function ($q) {
                return $q->added();
            })
            ->join('returns', 'return_details.return_id', '=', 'returns.id')
            ->get(['return_details.warehouse_id', 'product_id', 'quantity', 'issued_on', 'return_id AS model_id', 'return_details.created_at AS created', 'return_details.updated_at AS updated']);

        data_set($returnDetails, '*.model_type', 'App\Models\Returnn');
        data_set($returnDetails, '*.is_subtract', '0');

        InventoryHistory::insert($returnDetails->toArray());

        //Adjustment
        $adjustmentDetails = (new AdjustmentDetail)->query()
            ->whereHas('adjustment', function ($q) {
                return $q->adjusted();
            })
            ->join('adjustments', 'adjustment_details.adjustment_id', '=', 'adjustments.id')
            ->get(['adjustment_details.warehouse_id', 'product_id', 'quantity', 'issued_on', 'adjustment_id AS model_id', 'is_subtract AS is_subtract', 'adjustment_details.created_at AS created', 'adjustment_details.updated_at AS updated']);

        data_set($adjustmentDetails, '*.model_type', 'App\Models\Adjustment');

        InventoryHistory::insert($adjustmentDetails->toArray());

        //Reservation Reserveved
        $reservationDetails = (new ReservationDetail)->query()
            ->whereHas('reservation', function ($q) {
                return $q->reserved();
            })
            ->join('reservations', 'reservation_details.reservation_id', '=', 'reservations.id')
            ->get(['reservation_details.warehouse_id', 'product_id', 'quantity', 'issued_on', 'reservation_id AS model_id', 'reservation_details.created_at AS created', 'reservation_details.updated_at AS updated']);

        data_set($reservationDetails, '*.model_type', 'App\Models\Reservation');
        data_set($reservationDetails, '*.is_subtract', '1');

        InventoryHistory::insert($reservationDetails->toArray());

        //Reservation Cancelled
        $reservationDetails = (new ReservationDetail)->query()
            ->whereHas('reservation', function ($q) {
                return $q->cancelled();
            })
            ->join('reservations', 'reservation_details.reservation_id', '=', 'reservations.id')
            ->get(['reservation_details.warehouse_id', 'product_id', 'quantity', 'issued_on', 'reservation_id AS model_id', 'reservation_details.created_at AS created', 'reservation_details.updated_at AS updated']);

        data_set($reservationDetails, '*.model_type', 'App\Models\Reservation');
        data_set($reservationDetails, '*.is_subtract', '0');

        InventoryHistory::insert($reservationDetails->toArray());

        //JobExtra Add
        $jobExtraDetails = (new JobExtra)->query()
            ->whereHas('job', function ($q) {
                return $q->approved();
            })
            ->join('job_orders', 'job_extras.job_order_id', '=', 'job_orders.id')
            ->where('job_extras.status', '=', 'added')
            ->get(['factory_id AS warehouse_id', 'product_id', 'quantity', 'issued_on', 'job_order_id AS model_id', 'job_extras.created_at AS created', 'job_extras.updated_at AS updated']);

        data_set($jobExtraDetails, '*.model_type', 'App\Models\JobExtra');
        data_set($jobExtraDetails, '*.is_subtract', '0');

        InventoryHistory::insert($jobExtraDetails->toArray());

        //JobExtra Subtracted
        $jobExtraDetails = (new JobExtra)->query()
            ->whereHas('job', function ($q) {
                return $q->approved();
            })
            ->join('job_orders', 'job_extras.job_order_id', '=', 'job_orders.id')
            ->where('job_extras.status', '=', 'subtracted')
            ->get(['factory_id AS warehouse_id', 'product_id', 'quantity', 'issued_on', 'job_order_id AS model_id', 'job_extras.created_at AS created', 'job_extras.updated_at AS updated']);

        data_set($jobExtraDetails, '*.model_type', 'App\Models\JobExtra');
        data_set($jobExtraDetails, '*.is_subtract', '1');

        InventoryHistory::insert($jobExtraDetails->toArray());

        //JobDetailHistory
        $JobHistoryDetails = (new JobDetailHistory)->query()
            ->whereHas('jobDetail')
            ->join('job_details', 'job_detail_histories.job_detail_id', '=', 'job_details.id')
            ->join('job_orders', 'job_details.job_order_id', '=', 'job_orders.id')
            ->where('job_detail_histories.type', '=', 'subtracted')
            ->get(['job_detail_histories.product_id', 'job_detail_histories.quantity', 'job_detail_id AS model_id', 'issued_on', 'job_orders.factory_id AS warehouse_id', 'job_detail_histories.created_at AS created', 'job_detail_histories.updated_at AS updated']);

        data_set($JobHistoryDetails, '*.model_type', 'App\Models\JobDetail');
        data_set($JobHistoryDetails, '*.is_subtract', '1');

        InventoryHistory::insert($JobHistoryDetails->toArray());

        //JobDetailHistory
        $JobHistoryDetails = (new JobDetailHistory)->query()
            ->whereHas('jobDetail')
            ->join('job_details', 'job_detail_histories.job_detail_id', '=', 'job_details.id')
            ->join('job_orders', 'job_details.job_order_id', '=', 'job_orders.id')
            ->where('job_detail_histories.type', '=', 'added')
            ->get(['job_detail_histories.product_id', 'job_detail_histories.quantity', 'job_detail_id AS model_id', 'issued_on', 'job_orders.factory_id AS warehouse_id', 'job_detail_histories.created_at AS created', 'job_detail_histories.updated_at AS updated']);

        data_set($JobHistoryDetails, '*.model_type', 'App\Models\JobDetail');
        data_set($JobHistoryDetails, '*.is_subtract', '0');

        InventoryHistory::insert($JobHistoryDetails->toArray());

        DB::update('update inventory_histories set created_at = created, updated_at = updated');

        Schema::table('inventory_histories', function (Blueprint $table) {
            $table->dropColumn('created');
            $table->dropColumn('updated');
        });
    }

    public function down()
    {

    }
};