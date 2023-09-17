<?php

namespace App\Console\Commands;

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
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixInventoryHistoriesDataInconsistencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'temp-bug-fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ini_set('max_execution_time', '-1');

        DB::transaction(function () {
            Gdn::subtracted()->chunk(100, function ($gdns) {
                foreach ($gdns as $gdn) {
                    foreach ($gdn->gdnDetails()->orderBy('id', 'ASC')->get() as $gdnDetail) {
                        InventoryHistory::query()
                            ->where('is_subtract', 1)
                            ->whereNull('model_detail_type')
                            ->whereNull('model_detail_id')
                            ->where('model_type', 'App\Models\Gdn')
                            ->where('model_id', $gdnDetail->gdn_id)
                            ->orderBy('id', 'ASC')
                            ->limit(1)
                            ->update([
                                'model_detail_type' => get_class($gdnDetail),
                                'model_detail_id' => $gdnDetail->id,
                            ]);
                    }
                }
            });

            $this->info('gdn subtracted done');

            Gdn::added()->chunk(100, function ($gdns) {
                foreach ($gdns as $gdn) {
                    foreach ($gdn->gdnDetails()->orderBy('id', 'ASC')->get() as $gdnDetail) {
                        InventoryHistory::query()
                            ->where('is_subtract', 0)
                            ->whereNull('model_detail_type')
                            ->whereNull('model_detail_id')
                            ->where('model_type', 'App\Models\Gdn')
                            ->where('model_id', $gdnDetail->gdn_id)
                            ->orderBy('id', 'ASC')
                            ->limit(1)
                            ->update([
                                'model_detail_type' => get_class($gdnDetail),
                                'model_detail_id' => $gdnDetail->id,
                            ]);
                    }
                }
            });

            $this->info('gdn added done');

            Sale::subtracted()->chunk(100, function ($sales) {
                foreach ($sales as $sale) {
                    foreach ($sale->saleDetails()->orderBy('id', 'ASC')->get() as $saleDetail) {
                        InventoryHistory::query()
                            ->where('is_subtract', 1)
                            ->whereNull('model_detail_type')
                            ->whereNull('model_detail_id')
                            ->where('model_type', 'App\Models\Sale')
                            ->where('model_id', $saleDetail->sale_id)
                            ->orderBy('id', 'ASC')
                            ->limit(1)
                            ->update([
                                'model_detail_type' => get_class($saleDetail),
                                'model_detail_id' => $saleDetail->id,
                            ]);
                    }
                }
            });

            $this->info('sale subtracted done');

            Sale::added()->chunk(100, function ($sales) {
                foreach ($sales as $sale) {
                    foreach ($sale->saleDetails()->orderBy('id', 'ASC')->get() as $saleDetail) {
                        InventoryHistory::query()
                            ->where('is_subtract', 0)
                            ->whereNull('model_detail_type')
                            ->whereNull('model_detail_id')
                            ->where('model_type', 'App\Models\Sale')
                            ->where('model_id', $saleDetail->sale_id)
                            ->orderBy('id', 'ASC')
                            ->limit(1)
                            ->update([
                                'model_detail_type' => get_class($saleDetail),
                                'model_detail_id' => $saleDetail->id,
                            ]);
                    }
                }
            });

            $this->info('sale added done');

            Grn::added()->chunk(100, function ($grns) {
                foreach ($grns as $grn) {
                    $grn->grnDetails()->orderBy('id', 'ASC')->chunk(100, function ($grnDetails) {
                        foreach ($grnDetails as $grnDetail) {
                            InventoryHistory::query()
                                ->where('is_subtract', 0)
                                ->whereNull('model_detail_type')
                                ->whereNull('model_detail_id')
                                ->where('model_type', 'App\Models\Grn')
                                ->where('model_id', $grnDetail->grn_id)
                                ->orderBy('id', 'ASC')
                                ->limit(1)
                                ->update([
                                    'model_detail_type' => get_class($grnDetail),
                                    'model_detail_id' => $grnDetail->id,
                                ]);
                        }
                    });
                }
            });

            $this->info('grn done');

            Damage::subtracted()->chunk(100, function ($damages) {
                foreach ($damages as $damage) {
                    foreach ($damage->damageDetails()->orderBy('id', 'ASC')->get() as $damageDetail) {
                        InventoryHistory::query()
                            ->where('is_subtract', 1)
                            ->whereNull('model_detail_type')
                            ->whereNull('model_detail_id')
                            ->where('model_type', 'App\Models\Damage')
                            ->where('model_id', $damageDetail->damage_id)
                            ->orderBy('id', 'ASC')
                            ->limit(1)
                            ->update([
                                'model_detail_type' => get_class($damageDetail),
                                'model_detail_id' => $damageDetail->id,
                            ]);
                    }
                }
            });

            $this->info('damage done');

            Adjustment::adjusted()->chunk(100, function ($adjustments) {
                foreach ($adjustments as $adjustment) {
                    $adjustment->adjustmentDetails()->orderBy('id', 'ASC')->chunk(100, function ($adjustmentDetails) {
                        foreach ($adjustmentDetails as $adjustmentDetail) {
                            InventoryHistory::query()
                                ->whereNull('model_detail_type')
                                ->whereNull('model_detail_id')
                                ->where('model_type', 'App\Models\Adjustment')
                                ->where('model_id', $adjustmentDetail->adjustment_id)
                                ->orderBy('id', 'ASC')
                                ->limit(1)
                                ->update([
                                    'model_detail_type' => get_class($adjustmentDetail),
                                    'model_detail_id' => $adjustmentDetail->id,
                                ]);
                        }
                    });
                }
            });

            $this->info('adjustments done');

            Returnn::added()->chunk(100, function ($returns) {
                foreach ($returns as $return) {
                    foreach ($return->returnDetails()->orderBy('id', 'ASC')->get() as $returnDetail) {
                        InventoryHistory::query()
                            ->where('is_subtract', 0)
                            ->whereNull('model_detail_type')
                            ->whereNull('model_detail_id')
                            ->where('model_type', 'App\Models\Returnn')
                            ->where('model_id', $returnDetail->return_id)
                            ->orderBy('id', 'ASC')
                            ->limit(1)
                            ->update([
                                'model_detail_type' => get_class($returnDetail),
                                'model_detail_id' => $returnDetail->id,
                            ]);
                    }
                }
            });

            $this->info('return done');

            Transfer::subtracted()->chunk(100, function ($transfers) {
                foreach ($transfers as $transfer) {
                    foreach ($transfer->transferDetails()->orderBy('id', 'ASC')->get() as $transferDetail) {
                        InventoryHistory::query()
                            ->where('is_subtract', 1)
                            ->whereNull('model_detail_type')
                            ->whereNull('model_detail_id')
                            ->where('model_type', 'App\Models\Transfer')
                            ->where('model_id', $transferDetail->transfer_id)
                            ->orderBy('id', 'ASC')
                            ->limit(1)
                            ->update([
                                'model_detail_type' => get_class($transferDetail),
                                'model_detail_id' => $transferDetail->id,
                            ]);
                    }
                }
            });

            $this->info('transfer subtracted done');

            Transfer::added()->chunk(100, function ($transfers) {
                foreach ($transfers as $transfer) {
                    foreach ($transfer->transferDetails()->orderBy('id', 'ASC')->get() as $transferDetail) {
                        InventoryHistory::query()
                            ->where('is_subtract', 0)
                            ->whereNull('model_detail_type')
                            ->whereNull('model_detail_id')
                            ->where('model_type', 'App\Models\Transfer')
                            ->where('model_id', $transferDetail->transfer_id)
                            ->orderBy('id', 'ASC')
                            ->limit(1)
                            ->update([
                                'model_detail_type' => get_class($transferDetail),
                                'model_detail_id' => $transferDetail->id,
                            ]);
                    }
                }
            });

            $this->info('transfer added done');

            $reservationIds = InventoryHistory::where('model_type', 'App\Models\Reservation')->pluck('model_id')->unique()->toArray();

            Reservation::whereIn('id', $reservationIds)->chunk(100, function ($reservations) {
                foreach ($reservations as $reservation) {
                    foreach ($reservation->reservationDetails()->orderBy('id', 'ASC')->get() as $reservationDetail) {
                        InventoryHistory::query()
                            ->where('is_subtract', 0)
                            ->whereNull('model_detail_type')
                            ->whereNull('model_detail_id')
                            ->where('model_type', 'App\Models\Reservation')
                            ->where('model_id', $reservationDetail->reservation_id)
                            ->orderBy('id', 'ASC')
                            ->limit(1)
                            ->update([
                                'model_detail_type' => get_class($reservationDetail),
                                'model_detail_id' => $reservationDetail->reservation_id,
                            ]);
                    }
                }
            });

            $this->info('reservations added done');

            Reservation::whereIn('id', $reservationIds)->chunk(100, function ($reservations) {
                foreach ($reservations as $reservation) {
                    foreach ($reservation->reservationDetails()->orderBy('id', 'ASC')->get() as $reservationDetail) {
                        InventoryHistory::query()
                            ->where('is_subtract', 1)
                            ->whereNull('model_detail_type')
                            ->whereNull('model_detail_id')
                            ->where('model_type', 'App\Models\Reservation')
                            ->where('model_id', $reservationDetail->reservation_id)
                            ->orderBy('id', 'ASC')
                            ->limit(1)
                            ->update([
                                'model_detail_type' => get_class($reservationDetail),
                                'model_detail_id' => $reservationDetail->reservation_id,
                            ]);
                    }
                }
            });

            $this->info('reservations subtracted done');

            $jobDetails = JobDetail::whereIn('id', JobDetailHistory::where('type', 'subtracted')->pluck('job_detail_id'))->get();

            foreach ($jobDetails as $jobDetail) {
                foreach ($jobDetail->jobDetailHistories()->orderBy('id', 'ASC')->get() as $jobDetailHistory) {
                    InventoryHistory::query()
                        ->where('is_subtract', 1)
                        ->whereNull('model_detail_type')
                        ->whereNull('model_detail_id')
                        ->where('model_type', 'App\Models\Job')
                        ->where('model_id', $jobDetail->job_id)
                        ->orderBy('id', 'ASC')
                        ->limit(1)
                        ->update([
                            'model_detail_type' => get_class($jobDetailHistory),
                            'model_detail_id' => $jobDetailHistory->id,
                        ]);
                }
            }

            $this->info('job details subtracted done');

            $jobDetails = JobDetail::whereIn('id', JobDetailHistory::where('type', 'added')->pluck('job_detail_id'))->get();

            foreach ($jobDetails as $jobDetail) {
                foreach ($jobDetail->jobDetailHistories()->orderBy('id', 'ASC')->get() as $jobDetailHistory) {
                    InventoryHistory::query()
                        ->where('is_subtract', 0)
                        ->whereNull('model_detail_type')
                        ->whereNull('model_detail_id')
                        ->where('model_type', 'App\Models\Job')
                        ->where('model_id', $jobDetail->job_id)
                        ->orderBy('id', 'ASC')
                        ->limit(1)
                        ->update([
                            'model_detail_type' => get_class($jobDetailHistory),
                            'model_detail_id' => $jobDetailHistory->id,
                        ]);
                }
            }

            $this->info('job details added done');

            $transactionIds = InventoryHistory::where('model_type', 'App\Models\Transaction')->pluck('model_id')->unique()->toArray();

            Transaction::whereIn('id', $transactionIds)->chunk(100, function ($transactions) {
                foreach ($transactions as $transaction) {
                    foreach ($transaction->getTransactionDetails()->sortBy('id') as $transactionDetail) {
                        InventoryHistory::query()
                            ->whereNull('model_detail_type')
                            ->whereNull('model_detail_id')
                            ->where('model_type', 'App\Models\Transaction')
                            ->where('model_id', $transaction->id)
                            ->orderBy('id', 'ASC')
                            ->limit(1)
                            ->update([
                                'model_detail_type' => TransactionField::class,
                                'model_detail_id' => $transactionDetail['id'],
                            ]);
                    }
                }
            });

            $this->info('transactions done');
        });

        return Command::SUCCESS;
    }
}
