<?php

namespace App\Console\Commands;

use App\Models\Sale;
use App\Models\Warehouse;
use App\Services\Integrations\PointOfSaleService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemoveInvoicesByPos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pos:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove invoices by POS';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(PointOfSaleService $pointOfSaleService)
    {
        ini_set('max_execution_time', '-1');

        DB::transaction(function () use ($pointOfSaleService) {
            $warehouses = Warehouse::with('company')->active()->whereNotNull('pos_provider')->whereNotNull('host_address')->get();

            foreach ($warehouses as $warehouse) {
                if (!$warehouse->hasPosIntegration()) {
                    continue;
                }

                $sales = Sale::query()
                    ->approved()
                    ->notSubtracted()
                    ->notCancelled()
                    ->whereNull('fs_number')
                    ->where('warehouse_id', $warehouse->id)
                    ->orderBy('id', 'ASC')
                    ->get();

                foreach ($sales as $sale) {
                    if ($pointOfSaleService->exists($sale)) {
                        continue;
                    }

                    $sale->forceDelete();
                }
            }
        });

        return Command::SUCCESS;
    }
}
