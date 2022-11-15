<?php

use App\Models\AdjustmentDetail;
use App\Models\DamageDetail;
use App\Models\Gdn;
use App\Models\GdnDetail;
use App\Models\GrnDetail;
use App\Models\InventoryHistory;
use App\Models\JobDetail;
use App\Models\JobDetailHistory;
use App\Models\JobExtra;
use App\Models\Reservation;
use App\Models\ReservationDetail;
use App\Models\ReturnDetail;
use App\Models\TransferDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $jobDetails = JobDetail::query()->with('billOfMaterial.billOfMaterialDetails')->where('available', '>', 0)->orWhere('wip', '>', 0)->get();

        foreach ($jobDetails as $jobDetail) {
            $data = [];

            foreach ($jobDetail->billOfMaterial->billOfMaterialDetails as $billOfMaterialDetail) {
                if ($jobDetail->available > 0) {
                    $data['available'][] = [
                        'product_id' => $billOfMaterialDetail->product_id,
                        'quantity' => $jobDetail->available * $billOfMaterialDetail->quantity,
                        'type' => 'subtracted',
                    ];
                }

                if ($jobDetail->wip > 0) {
                    $data['wip'][] = [
                        'product_id' => $billOfMaterialDetail->product_id,
                        'quantity' => $jobDetail->wip * $billOfMaterialDetail->quantity,
                        'type' => 'subtracted',
                    ];
                }
            }

            if (isset($data['available'])) {
                $jobDetail->jobDetailHistories()->createMany($data['available']);

                $jobDetail->jobDetailHistories()->create([
                    'product_id' => $jobDetail->product_id,
                    'quantity' => $jobDetail->available,
                    'type' => 'added',
                ]);
            }

            if (isset($data['wip'])) {
                $jobDetail->jobDetailHistories()->createMany($data['wip']);
            }
        };

        //Grn
        $grnDetails = (new GrnDetail)->query()
            ->whereHas('grn', function ($q) {
                return $q->added();
            })
            ->join('grns', 'grn_details.grn_id', '=', 'grns.id')
            ->select(['company_id', 'grn_details.warehouse_id', 'product_id', 'quantity', 'issued_on', 'grns.id AS model_id', 'grns.created_at AS created', 'grns.updated_at AS updated'])
            ->get();

        data_set($grnDetails, '*.model_type', 'App\Models\Grn');
        data_set($grnDetails, '*.is_subtract', '0');

        foreach (array_chunk($grnDetails->toArray(), 1000) as $item) {
            InventoryHistory::insert($item);
        }

        //Gdn
        $gdnDetails = (new GdnDetail)->query()
            ->whereHas('gdn', function ($q) {
                return $q->subtracted();
            })
            ->join('gdns', 'gdn_details.gdn_id', '=', 'gdns.id')
            ->get(['company_id', 'gdn_details.warehouse_id', 'product_id', 'quantity', 'issued_on', 'gdn_id AS model_id', 'gdn_details.created_at AS created', 'gdn_details.updated_at AS updated']);

        data_set($gdnDetails, '*.model_type', 'App\Models\Gdn');
        data_set($gdnDetails, '*.is_subtract', '1');

        foreach (array_chunk($gdnDetails->toArray(), 1000) as $item) {
            InventoryHistory::insert($item);
        }

        //Transfer Subtracted
        $subtractedTransferDetails = (new TransferDetail)->query()
            ->whereHas('transfer', function ($q) {
                return $q->subtracted();
            })
            ->join('transfers', 'transfer_details.transfer_id', '=', 'transfers.id')
            ->get(['company_id', 'transferred_from AS warehouse_id', 'product_id', 'quantity', 'issued_on', 'transfer_id AS model_id', 'transfer_details.created_at AS created', 'transfer_details.updated_at AS updated']);

        data_set($subtractedTransferDetails, '*.model_type', 'App\Models\Transfer');
        data_set($subtractedTransferDetails, '*.is_subtract', '1');

        foreach (array_chunk($subtractedTransferDetails->toArray(), 1000) as $item) {
            InventoryHistory::insert($item);
        }

        //Transfer Add
        $addedTransferDetails = (new TransferDetail)->query()
            ->whereHas('transfer', function ($q) {
                return $q->added();
            })
            ->join('transfers', 'transfer_details.transfer_id', '=', 'transfers.id')
            ->get(['company_id', 'transferred_to AS warehouse_id', 'product_id', 'quantity', 'issued_on', 'transfer_id AS model_id', 'transfer_details.created_at AS created', 'transfer_details.updated_at AS updated']);

        data_set($addedTransferDetails, '*.model_type', 'App\Models\Transfer');
        data_set($addedTransferDetails, '*.is_subtract', '0');

        foreach (array_chunk($addedTransferDetails->toArray(), 1000) as $item) {
            InventoryHistory::insert($item);
        }

        //Damage
        $damageDetails = (new DamageDetail)->query()
            ->whereHas('damage', function ($q) {
                return $q->subtracted();
            })
            ->join('damages', 'damage_details.damage_id', '=', 'damages.id')
            ->get(['company_id', 'damage_details.warehouse_id', 'product_id', 'quantity', 'issued_on', 'damage_id AS model_id', 'damage_details.created_at AS created', 'damage_details.updated_at AS updated']);

        data_set($damageDetails, '*.model_type', 'App\Models\Damage');
        data_set($damageDetails, '*.is_subtract', '1');

        foreach (array_chunk($damageDetails->toArray(), 1000) as $item) {
            InventoryHistory::insert($item);
        }

        //Return
        $returnDetails = (new ReturnDetail)->query()
            ->whereHas('returnn', function ($q) {
                return $q->added();
            })
            ->join('returns', 'return_details.return_id', '=', 'returns.id')
            ->get(['company_id', 'return_details.warehouse_id', 'product_id', 'quantity', 'issued_on', 'return_id AS model_id', 'return_details.created_at AS created', 'return_details.updated_at AS updated']);

        data_set($returnDetails, '*.model_type', 'App\Models\Returnn');
        data_set($returnDetails, '*.is_subtract', '0');

        foreach (array_chunk($returnDetails->toArray(), 1000) as $item) {
            InventoryHistory::insert($item);
        }

        //Adjustment
        $adjustmentDetails = (new AdjustmentDetail)->query()
            ->whereHas('adjustment', function ($q) {
                return $q->adjusted();
            })
            ->join('adjustments', 'adjustment_details.adjustment_id', '=', 'adjustments.id')
            ->get(['company_id', 'adjustment_details.warehouse_id', 'product_id', 'quantity', 'issued_on', 'adjustment_id AS model_id', 'is_subtract AS is_subtract', 'adjustment_details.created_at AS created', 'adjustment_details.updated_at AS updated']);

        data_set($adjustmentDetails, '*.model_type', 'App\Models\Adjustment');

        foreach (array_chunk($adjustmentDetails->toArray(), 1000) as $item) {
            InventoryHistory::insert($item);
        }

        //Reservation
        $reservedReservationDetails = (new ReservationDetail)->query()
            ->whereHas('reservation', function ($q) {
                return $q->reserved()->notCancelled()->whereNotIn('id',
                    Reservation::whereHasMorph(
                        'reservable',
                        [Gdn::class],
                        function (Builder $query) {
                            $query->whereNotNull('subtracted_by');
                        })
                        ->pluck('id')
                );
            })
            ->join('reservations', 'reservation_details.reservation_id', '=', 'reservations.id')
            ->get(['company_id', 'reservation_details.warehouse_id', 'product_id', 'quantity', 'issued_on', 'reservation_id AS model_id', 'reservation_details.created_at AS created', 'reservation_details.updated_at AS updated']);

        data_set($reservedReservationDetails, '*.model_type', 'App\Models\Reservation');
        data_set($reservedReservationDetails, '*.is_subtract', '1');

        foreach (array_chunk($reservedReservationDetails->toArray(), 1000) as $item) {
            InventoryHistory::insert($item);
        }

        //JobExtra Add
        $addJobExtraDetails = (new JobExtra)->query()
            ->whereHas('job', function ($q) {
                return $q->approved();
            })
            ->join('job_orders', 'job_extras.job_order_id', '=', 'job_orders.id')
            ->where('job_extras.status', '=', 'added')
            ->get(['company_id', 'factory_id AS warehouse_id', 'product_id', 'quantity', 'issued_on', 'job_order_id AS model_id', 'job_extras.created_at AS created', 'job_extras.updated_at AS updated']);

        data_set($addJobExtraDetails, '*.model_type', 'App\Models\Job');
        data_set($addJobExtraDetails, '*.is_subtract', '0');

        foreach (array_chunk($addJobExtraDetails->toArray(), 1000) as $item) {
            InventoryHistory::insert($item);
        }

        //JobExtra Subtracted
        $subtractedJobExtraDetails = (new JobExtra)->query()
            ->whereHas('job', function ($q) {
                return $q->approved();
            })
            ->join('job_orders', 'job_extras.job_order_id', '=', 'job_orders.id')
            ->where('job_extras.status', '=', 'subtracted')
            ->get(['company_id', 'factory_id AS warehouse_id', 'product_id', 'quantity', 'issued_on', 'job_order_id AS model_id', 'job_extras.created_at AS created', 'job_extras.updated_at AS updated']);

        data_set($subtractedJobExtraDetails, '*.model_type', 'App\Models\Job');
        data_set($subtractedJobExtraDetails, '*.is_subtract', '1');

        foreach (array_chunk($subtractedJobExtraDetails->toArray(), 1000) as $item) {
            InventoryHistory::insert($item);
        }

        //JobDetailHistory Subtracted
        $subtractedJobHistoryDetails = (new JobDetailHistory)->query()
            ->whereHas('jobDetail')
            ->join('job_details', 'job_detail_histories.job_detail_id', '=', 'job_details.id')
            ->join('job_orders', 'job_details.job_order_id', '=', 'job_orders.id')
            ->where('job_detail_histories.type', '=', 'subtracted')
            ->get(['company_id', 'job_detail_histories.product_id', 'job_detail_histories.quantity', 'job_order_id AS model_id', 'issued_on', 'factory_id AS warehouse_id', 'job_detail_histories.created_at AS created', 'job_detail_histories.updated_at AS updated']);

        data_set($subtractedJobHistoryDetails, '*.model_type', 'App\Models\Job');
        data_set($subtractedJobHistoryDetails, '*.is_subtract', '1');

        foreach (array_chunk($subtractedJobHistoryDetails->toArray(), 1000) as $item) {
            InventoryHistory::insert($item);
        }

        //JobDetailHistory Add
        $addedJobHistoryDetails = (new JobDetailHistory)->query()
            ->whereHas('jobDetail')
            ->join('job_details', 'job_detail_histories.job_detail_id', '=', 'job_details.id')
            ->join('job_orders', 'job_details.job_order_id', '=', 'job_orders.id')
            ->where('job_detail_histories.type', '=', 'added')
            ->get(['company_id', 'job_detail_histories.product_id', 'job_detail_histories.quantity', 'job_order_id AS model_id', 'issued_on', 'factory_id AS warehouse_id', 'job_detail_histories.created_at AS created', 'job_detail_histories.updated_at AS updated']);

        data_set($addedJobHistoryDetails, '*.model_type', 'App\Models\Job');
        data_set($addedJobHistoryDetails, '*.is_subtract', '0');

        foreach (array_chunk($addedJobHistoryDetails->toArray(), 1000) as $item) {
            InventoryHistory::insert($item);
        }

        DB::update('update inventory_histories set created_at = created, updated_at = updated');

        Schema::table('inventory_histories', function (Blueprint $table) {
            $table->dropColumn('created');
            $table->dropColumn('updated');
        });
    }

    public function down()
    {
        JobDetailHistory::query()->forceDelete();
    }
};
