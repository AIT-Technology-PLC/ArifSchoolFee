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

        if ($total == 0) {
            return '100.00';
        }

        $percentage = number_format($accurate / $total * 100, 2);

        if ($inaccurate) {
            Log::channel('stack')->warning('Inventory level accuracy is at ' . $percentage . '%');
        }

        return $percentage;
    }

    private function getMerchandisesTableBadRows()
    {
        return DB::table('merchandises')
            ->whereNull('deleted_at')
            ->whereRaw('
                NOT EXISTS
                (
                    SELECT
                        *
                    FROM
                        inventory_histories
                    WHERE
                        merchandises.product_id = inventory_histories.product_id AND
                        merchandises.warehouse_id = inventory_histories.warehouse_id AND
                        inventory_histories.deleted_at IS NULL
                )
            ')->count();
    }

    private function getInventoryHistoriesTableBadRows()
    {
        return DB::table('inventory_histories')
            ->whereNull('deleted_at')
            ->whereRaw('
                NOT EXISTS
                (
                    SELECT
                        *
                    FROM
                        merchandises
                    WHERE
                        inventory_histories.product_id = merchandises.product_id AND
                        inventory_histories.warehouse_id = merchandises.warehouse_id AND
                        merchandises.deleted_at IS NULL
                )
            ')->count();
    }
}
