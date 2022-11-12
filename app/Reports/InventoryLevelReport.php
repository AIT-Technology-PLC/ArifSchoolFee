<?php

namespace App\Reports;

use App\Models\InventoryHistory;
use Illuminate\Support\Arr;

class InventoryLevelReport
{
    private $query;

    private $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;

        $this->setQuery();
    }

    public function __get($name)
    {
        if (!isset($this->$name)) {
            $this->$name = $this->$name();
        }

        return $this->$name;
    }

    private function setQuery()
    {
        $this->query = InventoryHistory::query()
            ->join('products', 'inventory_histories.product_id', '=', 'products.id')
            ->join('product_categories', 'products.product_category_id', '=', 'product_categories.id')
            ->join('warehouses', 'inventory_histories.warehouse_id', '=', 'warehouses.id')
            ->whereIn('warehouses.id', authUser()->getAllowedWarehouses('read')->pluck('id'))
            ->where('products.type', '!=', 'Services')
            ->when(isset($this->filters['date']), fn($q) => $q->whereDate('issued_on', '<=', $this->filters['date']))
            ->groupBy(['product_id', 'warehouse_id', 'product', 'type', 'unit', 'min_on_hand', 'category', 'warehouse'])
            ->selectRaw('
                products.name AS product,
                products.type as type,
                products.unit_of_measurement as unit,
                products.min_on_hand as min_on_hand,
                product_categories.name as category,
                warehouses.name as warehouse,
                inventory_histories.warehouse_id,
                inventory_histories.product_id,
                SUM(CASE WHEN inventory_histories.is_subtract = "1" THEN quantity*(-1) ELSE quantity END) AS quantity')
            ->get();
    }

    public function getInventoryLevels()
    {
        $inventoryHistory = (clone $this->query)->groupBy('product_id')->map->keyBy('warehouse');

        $organizedInventoryHistory = collect();

        foreach ($inventoryHistory as $inventoryHistoryKey => $inventoryHistoryValue) {
            $currentInventoryHistoryItem = [
                'product' => $inventoryHistoryValue->first()->product,
                'code' => $inventoryHistoryValue->first()->code ?? '',
                'product_id' => $inventoryHistoryValue->first()->product_id,
                'unit' => $inventoryHistoryValue->first()->unit,
                'type' => $inventoryHistoryValue->first()->type,
                'min_on_hand' => $inventoryHistoryValue->first()->min_on_hand,
                'description' => $inventoryHistoryValue->first()->description,
                'category' => $inventoryHistoryValue->first()->category,
                'total_balance' => $inventoryHistoryValue->sum('quantity'),
            ];

            foreach ($inventoryHistoryValue as $key => $value) {
                $currentInventoryHistoryItem = Arr::add($currentInventoryHistoryItem, $key, $value->quantity);
            }

            $organizedInventoryHistory->push($currentInventoryHistoryItem);
        }

        return $organizedInventoryHistory;
    }
}