<?php

namespace App\Console\Commands;

use App\Models\Sale;
use App\Models\Warehouse;
use App\Services\Integrations\PointOfSaleService;
use App\Services\Models\SaleService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class VoidInvoicesByPos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pos:void';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Void invoices by POS';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(PointOfSaleService $pointOfSaleService, SaleService $saleService)
    {
        ini_set('max_execution_time', '-1');

        $affectedRows = collect();

        DB::transaction(function () use ($pointOfSaleService, $saleService, $affectedRows) {
            $warehouses = Warehouse::with('company')->active()->whereNotNull('pos_provider')->whereNotNull('host_address')->get();

            foreach ($warehouses as $warehouse) {
                if (! $warehouse->hasPosIntegration()) {
                    continue;
                }

                $sales = Sale::query()
                    ->notSubtracted()
                    ->notCancelled()
                    ->whereNull('fs_number')
                    ->where('warehouse_id', $warehouse->id)
                    ->where('created_at', '<=', now()->subMinutes(5))
                    ->orderBy('id', 'ASC')
                    ->get();

                foreach ($sales as $sale) {
                    if (!$pointOfSaleService->isVoid($sale)) {
                        continue;
                    }

                    $saleService->cancel($sale);

                    $affectedRows->push($sale->id);
                }
            }
        });

        $this->info($affectedRows);

        return Command::SUCCESS;
    }
}
