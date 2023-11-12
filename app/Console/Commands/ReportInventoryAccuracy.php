<?php

namespace App\Console\Commands;

use App\Models\InventoryHistory;
use App\Models\Merchandise;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReportInventoryAccuracy extends Command
{
    protected $signature = 'inventory-accuracy';

    protected $description = 'Get inventory accuracy report';

    public function handle()
    {
        $rows = ['#', 'Description', 'Amount'];

        $this->table($rows, [
            [1, 'Merchandises Table Bad Rows', $this->getMerchandisesTableBadRows()],
            [2, 'Inventory Histories Table Bad Rows', $this->getInventoryHistoriesTableBadRows()],
            [3, 'Quantity Difference', $this->getInventoryLevelQuantityAccuracy()],
        ]);

        return 0;
    }

    private function getInventoryLevelQuantityAccuracy()
    {
        $merchandiseQuantity = Merchandise::query()->sum('available');

        $historyQuantity = InventoryHistory::selectRaw('
            SUM(
                CASE
                    WHEN inventory_histories.is_subtract = 1 THEN (inventory_histories.quantity * -1) ELSE inventory_histories.quantity
                END
            ) AS total_quantity
        ')->first()->total_quantity;

        return $merchandiseQuantity - $historyQuantity;
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
