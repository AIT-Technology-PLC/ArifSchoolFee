<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportInventoryAccuracy extends Command
{
    protected $signature = 'inventory-accuracy';

    protected $description = 'Get inventory accuracy report';

    private $query;

    public function handle()
    {
        $this->info('Merchandises Table Bad Rows: ' . $this->getMerchandisesTableBadRows());

        $this->info('Inventory Histories Table Bad Rows: ' . $this->getInventoryHistoriesTableBadRows());

        $this->info(str($this->getInventoryLevelAccuracy())->prepend('Inventory Level Accuracy: ')->append('%'));

        return 0;
    }

    private function setQuery()
    {
        $this->query = DB::table('inventory_histories')
            ->whereNull('deleted_at')
            ->selectRaw('
                product_id,
                warehouse_id,
                SUM(CASE WHEN is_subtract = 1 THEN quantity *(-1) ELSE quantity END) AS history_quantity,
                (
                    SELECT
                        merchandises.available
                    FROM
                        merchandises
                    WHERE
                        merchandises.product_id = inventory_histories.product_id AND
                        merchandises.warehouse_id = inventory_histories.warehouse_id AND
                        merchandises.deleted_at IS NULL
                ) AS merchandise_quantity
            ')
            ->groupBy(['product_id', 'warehouse_id']);
    }

    public function getInventoryLevelAccuracy()
    {
        $this->setQuery();

        $accurate = (clone $this->query)->havingRaw('merchandise_quantity = history_quantity')->count();

        $inaccurate = (clone $this->query)->havingRaw('merchandise_quantity <> history_quantity')->count();

        $total = $accurate + $inaccurate;

        $percentage = number_format($accurate / $total * 100, 2);

        if ($inaccurate) {
            Log::channel('stack')->warning('Inventory level accuracy is at ' . $percentage . '%');
        }

        return $percentage;
    }

    private function getMerchandisesTableBadRows()
    {
        $badRows = DB::table('merchandises')
            ->whereNull('deleted_at')
            ->whereRaw('
                merchandises.product_id NOT IN(
                    SELECT
                        inventory_histories.product_id
                    FROM
                        inventory_histories
                    WHERE
                        inventory_histories.deleted_at IS NULL
                )
            ')
            ->whereRaw('
                merchandises.warehouse_id NOT IN(
                    SELECT
                        inventory_histories.warehouse_id
                    FROM
                        inventory_histories
                    WHERE
                        inventory_histories.deleted_at IS NULL
                )
            ')->count();

        if ($badRows) {
            Log::channel('stack')->warning('Merchandises table has bad rows!');
        }

        return $badRows;
    }

    private function getInventoryHistoriesTableBadRows()
    {
        $badRows = DB::table('inventory_histories')
            ->whereNull('deleted_at')
            ->whereRaw('
                inventory_histories.product_id NOT IN(
                    SELECT
                        merchandises.product_id
                    FROM
                        merchandises
                    WHERE
                        merchandises.deleted_at IS NULL
                )
            ')
            ->whereRaw('
                inventory_histories.warehouse_id NOT IN(
                    SELECT
                        merchandises.warehouse_id
                    FROM
                        merchandises
                    WHERE
                        merchandises.deleted_at IS NULL
                )
            ')->count();

        if ($badRows) {
            Log::channel('stack')->warning('Inventory Histories table has bad rows!');
        }

        return $badRows;
    }
}
