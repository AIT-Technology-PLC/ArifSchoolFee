<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReportInventoryAccuracy extends Command
{
    protected $signature = 'inventory-accuracy';

    protected $description = 'Get inventory accuracy in percentage';

    private $query;

    public function handle()
    {
        $this->setQuery();

        $accurate = (clone $this->query)->havingRaw('merchandise_quantity = history_quantity')->count();

        $inaccurate = (clone $this->query)->havingRaw('merchandise_quantity <> history_quantity')->count();

        $total = $accurate + $inaccurate;

        $percentage = number_format($accurate / $total * 100, 2);

        $percentage < 100 ? $this->error(str($percentage)->append('%')) : $this->info(str($percentage)->append('%'));

        return 0;
    }

    private function setQuery()
    {
        $this->query = DB::table('inventory_histories')
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
                    merchandises.product_id = inventory_histories.product_id AND merchandises.warehouse_id = inventory_histories.warehouse_id
                ) AS merchandise_quantity
            ')
            ->groupBy(['product_id', 'warehouse_id']);
    }
}
