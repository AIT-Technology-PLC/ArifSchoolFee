<?php

namespace App\Reports;

use App\Models\Merchandise;

class InventoryValuationReport
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
        $this->query = Merchandise::query()
            ->available()
            ->withoutGlobalScopes([BranchScope::class])
            ->join('products', 'merchandises.product_id', '=', 'products.id')
            ->join('warehouses', 'merchandises.warehouse_id', '=', 'warehouses.id')
            ->when(isset($this->filters['branches']), fn($q) => $q->whereIn('merchandises.warehouse_id', $this->filters['branches']));
    }

    public function getValuationByProducts()
    {
        return (clone $this->query)
            ->selectRaw('
                CASE WHEN products.inventory_valuation_method = "fifo" THEN fifo_unit_cost
                    WHEN products.inventory_valuation_method = "lifo" THEN lifo_unit_cost
                    WHEN products.inventory_valuation_method = "average" THEN average_unit_cost
                    ELSE NULL
                END AS unit_cost,
                products.name AS product_name,
                products.code AS product_code,
                SUM(merchandises.available) AS quantity,
                products.unit_of_measurement AS unit_of_measurement
            ')
            ->groupBy('product_code', 'unit_of_measurement', 'unit_cost', 'product_name')
            ->get();
    }

    public function getValuationByBranch()
    {
        return (clone $this->query)
            ->selectRaw('
            warehouses.name AS warehouse_name,
            SUM(
                CASE WHEN products.inventory_valuation_method = "fifo" THEN fifo_unit_cost
                     WHEN products.inventory_valuation_method = "lifo" THEN lifo_unit_cost
                     WHEN products.inventory_valuation_method = "average" THEN average_unit_cost
                     ELSE 0
                END * merchandises.available
            ) AS total_cost
        ')
            ->groupBy('warehouses.name')
            ->get();
    }

    public function getTotalInventoryValue()
    {
        return (clone $this->query)
            ->selectRaw('
            SUM(
                CASE WHEN products.inventory_valuation_method = "fifo" THEN fifo_unit_cost
                     WHEN products.inventory_valuation_method = "lifo" THEN lifo_unit_cost
                     WHEN products.inventory_valuation_method = "average" THEN average_unit_cost
                     ELSE 0
                END * merchandises.available
            ) AS total_cost
        ')->first();
    }

    public function getTotalProductsInInventory()
    {
        return (clone $this->query)->distinct('product_id')->count();
    }
}
