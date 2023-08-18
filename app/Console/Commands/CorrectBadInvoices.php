<?php

namespace App\Console\Commands;

use App\Models\Sale;
use App\Models\Warehouse;
use App\Services\Integrations\PointOfSaleService;
use App\Services\Models\SaleService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CorrectBadInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pos:correct-bad-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Correct bad invoices';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(PointOfSaleService $pointOfSaleService, SaleService $saleService)
    {
        ini_set('max_execution_time', '-1');

        $badInvoices = collect();

        DB::transaction(function () use ($pointOfSaleService, $saleService, $badInvoices) {
            $warehouses = Warehouse::with('company')->active()->whereNotNull('pos_provider')->whereNotNull('host_address')->get();

            foreach ($warehouses as $warehouse) {
                if (! $warehouse->hasPosIntegration()) {
                    continue;
                }

                $sales = Sale::whereNull('fs_number')->where('warehouse_id', $warehouse->id)->orderBy('id', 'ASC')->get();

                foreach ($sales as $sale) {
                    [$isExecuted, $fsNumber] = $pointOfSaleService->getFsNumber($sale);

                    if (!$isExecuted || empty($fsNumber)) {
                        continue;
                    }

                    $sale->undoCancel();

                    $sale->approve();

                    $saleService->assignFSNumber([
                        'invoice_number' => $sale->code,
                        'fs_number' => $fsNumber,
                    ]);

                    $badInvoices->push([
                        'sale_id' => $sale->id,
                        'fs_number' => $fsNumber,
                    ]);
                }
            }
        });

        $this->info($badInvoices);

        return Command::SUCCESS;
    }
}
