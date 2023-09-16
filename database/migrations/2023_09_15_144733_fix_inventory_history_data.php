<?php

use App\Models\Adjustment;
use App\Models\Damage;
use App\Models\Gdn;
use App\Models\Grn;
use App\Models\InventoryHistory;
use App\Models\JobDetail;
use App\Models\JobDetailHistory;
use App\Models\Reservation;
use App\Models\Returnn;
use App\Models\Sale;
use App\Models\Transaction;
use App\Models\TransactionField;
use App\Models\Transfer;
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
        // ini_set('max_execution_time', '-1');

        // DB::transaction(function () {
        //     foreach (Gdn::subtracted()->get() as $gdn) {
        //         foreach ($gdn->gdnDetails()->orderBy('id', 'ASC')->get() as $gdnDetail) {
        //             InventoryHistory::query()
        //                 ->where('is_subtract', 1)
        //                 ->whereNull('model_detail_type')
        //                 ->whereNull('model_detail_id')
        //                 ->where('model_type', 'App\Models\Gdn')
        //                 ->where('model_id', $gdnDetail->gdn_id)
        //                 ->orderBy('id', 'ASC')
        //                 ->limit(1)
        //                 ->update([
        //                     'model_detail_type' => get_class($gdnDetail),
        //                     'model_detail_id' => $gdnDetail->id,
        //                 ]);
        //         }
        //     }

        //     foreach (Gdn::added()->get() as $gdn) {
        //         foreach ($gdn->gdnDetails()->orderBy('id', 'ASC')->get() as $gdnDetail) {
        //             InventoryHistory::query()
        //                 ->where('is_subtract', 0)
        //                 ->whereNull('model_detail_type')
        //                 ->whereNull('model_detail_id')
        //                 ->where('model_type', 'App\Models\Gdn')
        //                 ->where('model_id', $gdnDetail->gdn_id)
        //                 ->orderBy('id', 'ASC')
        //                 ->limit(1)
        //                 ->update([
        //                     'model_detail_type' => get_class($gdnDetail),
        //                     'model_detail_id' => $gdnDetail->id,
        //                 ]);
        //         }
        //     }

        //     foreach (Sale::subtracted()->get() as $sale) {
        //         foreach ($sale->saleDetails()->orderBy('id', 'ASC')->get() as $saleDetail) {
        //             InventoryHistory::query()
        //                 ->where('is_subtract', 1)
        //                 ->whereNull('model_detail_type')
        //                 ->whereNull('model_detail_id')
        //                 ->where('model_type', 'App\Models\Sale')
        //                 ->where('model_id', $saleDetail->sale_id)
        //                 ->orderBy('id', 'ASC')
        //                 ->limit(1)
        //                 ->update([
        //                     'model_detail_type' => get_class($saleDetail),
        //                     'model_detail_id' => $saleDetail->id,
        //                 ]);
        //         }
        //     }

        //     foreach (Sale::added()->get() as $sale) {
        //         foreach ($sale->saleDetails()->orderBy('id', 'ASC')->get() as $saleDetail) {
        //             InventoryHistory::query()
        //                 ->where('is_subtract', 0)
        //                 ->whereNull('model_detail_type')
        //                 ->whereNull('model_detail_id')
        //                 ->where('model_type', 'App\Models\Sale')
        //                 ->where('model_id', $saleDetail->sale_id)
        //                 ->orderBy('id', 'ASC')
        //                 ->limit(1)
        //                 ->update([
        //                     'model_detail_type' => get_class($saleDetail),
        //                     'model_detail_id' => $saleDetail->id,
        //                 ]);
        //         }
        //     }

        //     foreach (Grn::added()->get() as $grn) {
        //         foreach ($grn->grnDetails()->orderBy('id', 'ASC')->get() as $grnDetail) {
        //             InventoryHistory::query()
        //                 ->where('is_subtract', 0)
        //                 ->whereNull('model_detail_type')
        //                 ->whereNull('model_detail_id')
        //                 ->where('model_type', 'App\Models\Grn')
        //                 ->where('model_id', $grnDetail->grn_id)
        //                 ->orderBy('id', 'ASC')
        //                 ->limit(1)
        //                 ->update([
        //                     'model_detail_type' => get_class($grnDetail),
        //                     'model_detail_id' => $grnDetail->id,
        //                 ]);
        //         }
        //     }

        //     foreach (Damage::subtracted()->get() as $damage) {
        //         foreach ($damage->damageDetails()->orderBy('id', 'ASC')->get() as $damageDetail) {
        //             InventoryHistory::query()
        //                 ->where('is_subtract', 1)
        //                 ->whereNull('model_detail_type')
        //                 ->whereNull('model_detail_id')
        //                 ->where('model_type', 'App\Models\Damage')
        //                 ->where('model_id', $damageDetail->damage_id)
        //                 ->orderBy('id', 'ASC')
        //                 ->limit(1)
        //                 ->update([
        //                     'model_detail_type' => get_class($damageDetail),
        //                     'model_detail_id' => $damageDetail->id,
        //                 ]);
        //         }
        //     }

        //     foreach (Adjustment::adjusted()->get() as $adjustment) {
        //         foreach ($adjustment->adjustmentDetails()->where('is_subtract', 1)->orderBy('id', 'ASC')->get() as $adjustmentDetail) {
        //             InventoryHistory::query()
        //                 ->where('is_subtract', 1)
        //                 ->whereNull('model_detail_type')
        //                 ->whereNull('model_detail_id')
        //                 ->where('model_type', 'App\Models\Adjustment')
        //                 ->where('model_id', $adjustmentDetail->adjustment_id)
        //                 ->orderBy('id', 'ASC')
        //                 ->limit(1)
        //                 ->update([
        //                     'model_detail_type' => get_class($adjustmentDetail),
        //                     'model_detail_id' => $adjustmentDetail->id,
        //                 ]);
        //         }

        //         foreach ($adjustment->adjustmentDetails()->where('is_subtract', 0)->orderBy('id', 'ASC')->get() as $adjustmentDetail) {
        //             InventoryHistory::query()
        //                 ->where('is_subtract', 0)
        //                 ->whereNull('model_detail_type')
        //                 ->whereNull('model_detail_id')
        //                 ->where('model_type', 'App\Models\Adjustment')
        //                 ->where('model_id', $adjustmentDetail->adjustment_id)
        //                 ->orderBy('id', 'ASC')
        //                 ->limit(1)
        //                 ->update([
        //                     'model_detail_type' => get_class($adjustmentDetail),
        //                     'model_detail_id' => $adjustmentDetail->id,
        //                 ]);
        //         }
        //     }

        //     foreach (Returnn::added()->get() as $return) {
        //         foreach ($return->returnDetails()->orderBy('id', 'ASC')->get() as $returnDetail) {
        //             InventoryHistory::query()
        //                 ->where('is_subtract', 0)
        //                 ->whereNull('model_detail_type')
        //                 ->whereNull('model_detail_id')
        //                 ->where('model_type', 'App\Models\Returnn')
        //                 ->where('model_id', $returnDetail->return_id)
        //                 ->orderBy('id', 'ASC')
        //                 ->limit(1)
        //                 ->update([
        //                     'model_detail_type' => get_class($returnDetail),
        //                     'model_detail_id' => $returnDetail->id,
        //                 ]);
        //         }
        //     }

        //     foreach (Transfer::subtracted()->get() as $transfer) {
        //         foreach ($transfer->transferDetails()->orderBy('id', 'ASC')->get() as $transferDetail) {
        //             InventoryHistory::query()
        //                 ->where('is_subtract', 1)
        //                 ->whereNull('model_detail_type')
        //                 ->whereNull('model_detail_id')
        //                 ->where('model_type', 'App\Models\Transfer')
        //                 ->where('model_id', $transferDetail->transfer_id)
        //                 ->orderBy('id', 'ASC')
        //                 ->limit(1)
        //                 ->update([
        //                     'model_detail_type' => get_class($transferDetail),
        //                     'model_detail_id' => $transferDetail->id,
        //                 ]);
        //         }
        //     }

        //     foreach (Transfer::added()->get() as $transfer) {
        //         foreach ($transfer->transferDetails()->orderBy('id', 'ASC')->get() as $transferDetail) {
        //             InventoryHistory::query()
        //                 ->where('is_subtract', 0)
        //                 ->whereNull('model_detail_type')
        //                 ->whereNull('model_detail_id')
        //                 ->where('model_type', 'App\Models\Transfer')
        //                 ->where('model_id', $transferDetail->transfer_id)
        //                 ->orderBy('id', 'ASC')
        //                 ->limit(1)
        //                 ->update([
        //                     'model_detail_type' => get_class($transferDetail),
        //                     'model_detail_id' => $transferDetail->id,
        //                 ]);
        //         }
        //     }

        //     $reservationIds = InventoryHistory::where('model_type', 'App\Models\Reservation')->pluck('model_id')->unique()->toArray();

        //     foreach (Reservation::whereIn('id', $reservationIds)->get() as $reservation) {
        //         foreach ($reservation->reservationDetails()->orderBy('id', 'ASC')->get() as $reservationDetail) {
        //             InventoryHistory::query()
        //                 ->where('is_subtract', 0)
        //                 ->whereNull('model_detail_type')
        //                 ->whereNull('model_detail_id')
        //                 ->where('model_type', 'App\Models\Reservation')
        //                 ->where('model_id', $reservationDetail->reservation_id)
        //                 ->orderBy('id', 'ASC')
        //                 ->limit(1)
        //                 ->update([
        //                     'model_detail_type' => get_class($reservationDetail),
        //                     'model_detail_id' => $reservationDetail->reservation_id,
        //                 ]);
        //         }
        //     }

        //     foreach (Reservation::whereIn('id', $reservationIds)->get() as $reservation) {
        //         foreach ($reservation->reservationDetails()->orderBy('id', 'ASC')->get() as $reservationDetail) {
        //             InventoryHistory::query()
        //                 ->where('is_subtract', 1)
        //                 ->whereNull('model_detail_type')
        //                 ->whereNull('model_detail_id')
        //                 ->where('model_type', 'App\Models\Reservation')
        //                 ->where('model_id', $reservationDetail->reservation_id)
        //                 ->orderBy('id', 'ASC')
        //                 ->limit(1)
        //                 ->update([
        //                     'model_detail_type' => get_class($reservationDetail),
        //                     'model_detail_id' => $reservationDetail->reservation_id,
        //                 ]);
        //         }
        //     }

        //     $jobDetails = JobDetail::whereIn('id', JobDetailHistory::where('type', 'subtracted')->pluck('job_detail_id'))->get();

        //     foreach ($jobDetails as $jobDetail) {
        //         foreach ($jobDetail->jobDetailHistories()->orderBy('id', 'ASC')->get() as $jobDetailHistory) {
        //             InventoryHistory::query()
        //                 ->where('is_subtract', 1)
        //                 ->whereNull('model_detail_type')
        //                 ->whereNull('model_detail_id')
        //                 ->where('model_type', 'App\Models\Job')
        //                 ->where('model_id', $jobDetail->job_id)
        //                 ->orderBy('id', 'ASC')
        //                 ->limit(1)
        //                 ->update([
        //                     'model_detail_type' => get_class($jobDetailHistory),
        //                     'model_detail_id' => $jobDetailHistory->id,
        //                 ]);
        //         }
        //     }

        //     $jobDetails = JobDetail::whereIn('id', JobDetailHistory::where('type', 'added')->pluck('job_detail_id'))->get();

        //     foreach ($jobDetails as $jobDetail) {
        //         foreach ($jobDetail->jobDetailHistories()->orderBy('id', 'ASC')->get() as $jobDetailHistory) {
        //             InventoryHistory::query()
        //                 ->where('is_subtract', 0)
        //                 ->whereNull('model_detail_type')
        //                 ->whereNull('model_detail_id')
        //                 ->where('model_type', 'App\Models\Job')
        //                 ->where('model_id', $jobDetail->job_id)
        //                 ->orderBy('id', 'ASC')
        //                 ->limit(1)
        //                 ->update([
        //                     'model_detail_type' => get_class($jobDetailHistory),
        //                     'model_detail_id' => $jobDetailHistory->id,
        //                 ]);
        //         }
        //     }

        //     $transactionIds = InventoryHistory::where('model_type', 'App\Models\Transaction')->pluck('model_id')->unique()->toArray();

        //     foreach (Transaction::whereIn('id', $transactionIds)->get() as $transaction) {
        //         foreach ($transaction->getTransactionDetails()->sortBy('id') as $transactionDetail) {
        //             InventoryHistory::query()
        //                 ->whereNull('model_detail_type')
        //                 ->whereNull('model_detail_id')
        //                 ->where('model_type', 'App\Models\Transaction')
        //                 ->where('model_id', $transaction->id)
        //                 ->orderBy('id', 'ASC')
        //                 ->limit(1)
        //                 ->update([
        //                     'model_detail_type' => TransactionField::class,
        //                     'model_detail_id' => $transactionDetail['id'],
        //                 ]);
        //         }
        //     }
        // });
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
