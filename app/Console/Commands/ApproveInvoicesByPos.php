<?php

namespace App\Console\Commands;

use App\Models\Sale;
use App\Models\Warehouse;
use App\Services\Integrations\PointOfSaleService;
use App\Services\Models\SaleService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ApproveInvoicesByPos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pos:approve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Approve invoices by POS';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(PointOfSaleService $pointOfSaleService, SaleService $saleService)
    {
        ini_set('max_execution_time', '-1');

        DB::transaction(function () use ($pointOfSaleService, $saleService) {
            $warehouses = Warehouse::with('company')->active()->whereNotNull('pos_provider')->whereNotNull('host_address')->get();

            foreach ($warehouses as $warehouse) {
                if (!$warehouse->hasPosIntegration()) {
                    continue;
                }

                $sales = Sale::query()
                    ->notSubtracted()
                    ->notCancelled()
                    ->whereNull('fs_number')
                    ->where('warehouse_id', $warehouse->id)
                    ->orderBy('id', 'ASC')
                    ->get();

                foreach ($sales as $sale) {
                    [$isExecuted, $fsNumber] = $pointOfSaleService->getFsNumber($sale);

                    if (!$isExecuted || empty($fsNumber)) {
                        continue;
                    }

                    if (!$sale->isApproved()) {
                        $sale->approve();
                    }

                    $saleService->assignFSNumber([
                        'warehouse_id' => $sale->warehouse_id,
                        'invoice_number' => $sale->code,
                        'fs_number' => $fsNumber,
                    ]);
                }
            }
        });

        return Command::SUCCESS;
    }
}
