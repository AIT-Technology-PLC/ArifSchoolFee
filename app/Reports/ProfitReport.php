<?php

namespace App\Reports;

use App\Models\InventoryHistory;
use App\Models\InventoryValuationBalance;

class ProfitReport
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
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->whereIn('warehouses.id', authUser()->getAllowedWarehouses('read')->pluck('id'))
            ->where('products.type', '!=', 'Services')
            ->when(isset($this->filters['branches']), fn($q) => $q->whereIn('inventory_histories.warehouse_id', $this->filters['branches']))
            ->when(isset($this->filters['period']), fn($q) => $q->whereDate('inventory_histories.issued_on', '>=', $this->filters['period'][0])->whereDate('inventory_histories.issued_on', '<=', $this->filters['period'][1]))
            ->when(isset($this->filters['product_id']), fn($q) => $q->where('inventory_histories.product_id', $this->filters['product_id']))
            ->when(isset($this->filters['brand_id']), fn($q) => $q->where('brand_id', $this->filters['brand_id']))
            ->when(isset($this->filters['category_id']), fn($q) => $q->where('product_category_id', $this->filters['category_id']));
    }

    public function getNewCosts()
    {
        return InventoryValuationBalance::where('type', 'fifo')->when(isset($this->filters['period']), fn($q) => $q->whereDate('created_at', '>=', $this->filters['period'][0])->whereDate('created_at', '<=', $this->filters['period'][1]))->selectRaw('SUM(original_quantity*unit_cost) as total_cost')->first()->total_cost;
    }

    public function getBeginningInventoryCost()
    {
        return $this->query
            ->when(isset($this->filters['period']), fn($q) => $q->whereDate('inventory_histories.issued_on', '>', $this->filters['period'][0]))
            ->selectRaw('SUM(CASE
                        WHEN products.inventory_valuation_method = "fifo" THEN inventory_histories.quantity * products.fifo_unit_cost
                        WHEN products.inventory_valuation_method = "lifo" THEN inventory_histories.quantity * products.lifo_unit_cost
                        WHEN products.inventory_valuation_method = "average" THEN inventory_histories.quantity * products.average_unit_cost
                        ELSE 0
                    END) AS total_valuation')
            ->first();
    }

    public function getEndingInventoryCost()
    {
        return $this->query
            ->when(isset($this->filters['period']), fn($q) => $q->whereDate('inventory_histories.issued_on', '<=', $this->filters['period'][1]))
            ->selectRaw('SUM(CASE
                        WHEN products.inventory_valuation_method = "fifo" THEN inventory_histories.quantity * products.fifo_unit_cost
                        WHEN products.inventory_valuation_method = "lifo" THEN inventory_histories.quantity * products.lifo_unit_cost
                        WHEN products.inventory_valuation_method = "average" THEN inventory_histories.quantity * products.average_unit_cost
                        ELSE 0
                    END) AS total_valuation')
            ->first();
    }

    public function getCostOfGoodsSold()
    {
        return $this->getBeginningInventoryCost->total_valuation + $this->getNewCosts() - $this->getEndingInventoryCost()->total_valuation;
    }

    public function getProfitByProducts()
    {
        return (clone $this->query)
            ->selectRaw('
                products.name AS product_name,
                SUM(
                    CASE WHEN products.inventory_valuation_method = "fifo" THEN inventory_histories.quantity * products.fifo_unit_cost
                        WHEN products.inventory_valuation_method = "lifo" THEN inventory_histories.quantity * products.lifo_unit_cost
                        WHEN products.inventory_valuation_method = "average" THEN inventory_histories.quantity * products.average_unit_cost
                        ELSE 0
                    END
                ) AS total_cost
            ')
            ->groupBy('product_id', 'product_name')
            ->get();
    }

    public function getProfitByCategories()
    {
        return (clone $this->query)
            ->selectRaw('
            product_categories.name AS category_name,
            SUM(
                CASE WHEN products.inventory_valuation_method = "fifo" THEN inventory_histories.quantity * products.fifo_unit_cost
                    WHEN products.inventory_valuation_method = "lifo" THEN inventory_histories.quantity * products.lifo_unit_cost
                    WHEN products.inventory_valuation_method = "average" THEN inventory_histories.quantity * products.average_unit_cost
                    ELSE 0
                END
            ) AS total_cost
        ')
            ->groupBy('product_categories.name')
            ->get();
    }

    public function getProfitByBranchs()
    {
        return (clone $this->query)
            ->selectRaw('
            warehouses.name AS branch_name,
            SUM(
                CASE WHEN products.inventory_valuation_method = "fifo" THEN inventory_histories.quantity * products.fifo_unit_cost
                    WHEN products.inventory_valuation_method = "lifo" THEN inventory_histories.quantity * products.lifo_unit_cost
                    WHEN products.inventory_valuation_method = "average" THEN inventory_histories.quantity * products.average_unit_cost
                    ELSE 0
                END
            ) AS total_cost
        ')
            ->groupBy('warehouses.name')
            ->get();
    }

    public function getProfitByBrands()
    {
        return (clone $this->query)
            ->selectRaw('
            brands.name AS brand_name,
            SUM(
                CASE WHEN products.inventory_valuation_method = "fifo" THEN inventory_histories.quantity * products.fifo_unit_cost
                    WHEN products.inventory_valuation_method = "lifo" THEN inventory_histories.quantity * products.lifo_unit_cost
                    WHEN products.inventory_valuation_method = "average" THEN inventory_histories.quantity * products.average_unit_cost
                    ELSE 0
                END
            ) AS total_cost
        ')
            ->groupBy('brands.name')
            ->get();
    }
}
