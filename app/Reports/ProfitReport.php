<?php

namespace App\Reports;

use App\Models\InventoryValuationBalance;
use App\Models\Product;

class ProfitReport
{
    private $filters;

    private $details;

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
        $source = ReportSource::getSalesReportInput($this->filters);

        $this->details = $source['details'];

        $this->master = $source['master'];
    }

    public function getTotalRevenueBeforeTax()
    {
        return (clone $this->details)->sum('line_price_before_tax');
    }

    public function getProfit()
    {
        return $this->getTotalRevenueBeforeTax - $this->getCostOfGoodsSold;
    }

    public function getProfitMarginInPercentage()
    {
        if ($this->getTotalRevenueBeforeTax == 0) {
            return 0;
        }

        return $this->getProfit / $this->getTotalRevenueBeforeTax * 100;
    }

    public function getNewCosts()
    {
        return InventoryValuationBalance::query()
            ->when(isset($this->filters['product_id']), fn($query) => $query->where('product_id', $this->filters['product_id']))
            ->where('type', 'lifo')
            ->where('created_at', '>=', carbon($this->filters['period'][0])->startOfDay())
            ->where('created_at', '<=', carbon($this->filters['period'][1])->endOfDay())
            ->selectRaw('SUM(original_quantity*unit_cost) AS total_cost')
            ->first()
            ->total_cost;
    }

    public function getBeginningInventoryCost()
    {
        return Product::inventoryType()
            ->when(isset($this->filters['product_id']), fn($query) => $query->where('id', $this->filters['product_id']))
            ->selectRaw("
                SUM(
                   (SELECT inventory_valuation_histories.unit_cost FROM inventory_valuation_histories WHERE inventory_valuation_histories.product_id = products.id AND inventory_valuation_histories.type = products.inventory_valuation_method AND inventory_valuation_histories.created_at <= '" . carbon($this->filters['period'][0])->startOfDay() . "' AND inventory_valuation_histories.deleted_at is NULL ORDER BY inventory_valuation_histories.id DESC LIMIT 1)
                   *
                   (SELECT SUM(CASE WHEN inventory_histories.is_subtract = 1 THEN inventory_histories.quantity*(-1) ELSE inventory_histories.quantity END) FROM inventory_histories WHERE inventory_histories.product_id = products.id AND inventory_histories.issued_on < '" . carbon($this->filters['period'][0])->startOfDay() . "' AND inventory_histories.deleted_at IS NULL GROUP BY inventory_histories.product_id)
                ) AS total_cost
            ")
            ->first()
            ->total_cost;
    }

    public function getEndingInventoryCost()
    {
        return Product::inventoryType()
            ->when(isset($this->filters['product_id']), fn($query) => $query->where('id', $this->filters['product_id']))
            ->selectRaw("
                SUM(
                   (SELECT inventory_valuation_histories.unit_cost FROM inventory_valuation_histories WHERE inventory_valuation_histories.product_id = products.id AND inventory_valuation_histories.type = products.inventory_valuation_method AND inventory_valuation_histories.created_at <= '" . carbon($this->filters['period'][1])->endOfDay() . "' AND inventory_valuation_histories.deleted_at is NULL ORDER BY inventory_valuation_histories.id DESC LIMIT 1)
                   *
                   (SELECT SUM(CASE WHEN inventory_histories.is_subtract = 1 THEN inventory_histories.quantity*(-1) ELSE inventory_histories.quantity END) FROM inventory_histories WHERE inventory_histories.product_id = products.id AND inventory_histories.issued_on <= '" . carbon($this->filters['period'][1])->endOfDay() . "' AND inventory_histories.deleted_at IS NULL GROUP BY inventory_histories.product_id)
                ) AS total_cost
            ")
            ->first()
            ->total_cost;
    }

    public function getCostOfGoodsSold()
    {
        return ($this->getBeginningInventoryCost + $this->getNewCosts) - $this->getEndingInventoryCost;
    }

    public function getProfitByProducts()
    {
        return (clone $this->details)
            ->selectRaw('
                SUM(line_price_before_tax) AS revenue,
                SUM(unit_cost*quantity) AS total_cost,
                SUM(line_price_before_tax) - COALESCE(SUM(unit_cost*quantity), 0) AS profit,
                SUM(quantity) AS quantity,
                product_name,
                product_code,
                product_unit_of_measurement
            ')
            ->groupBy('product_id')
            ->orderByDesc('revenue')
            ->get();
    }

    public function getProfitByCategories()
    {
        return (clone $this->details)
            ->selectRaw('
                SUM(line_price_before_tax) AS revenue,
                SUM(unit_cost*quantity) AS total_cost,
                SUM(line_price_before_tax) - COALESCE(SUM(unit_cost*quantity), 0) AS profit,
                SUM(quantity) AS quantity,
                product_category_name
            ')
            ->groupBy('product_category_id')
            ->orderByDesc('revenue')
            ->get();
    }

    public function getProfitByBranches()
    {
        return (clone $this->details)
            ->selectRaw('
                SUM(line_price_before_tax) AS revenue,
                SUM(unit_cost*quantity) AS total_cost,
                SUM(line_price_before_tax) - COALESCE(SUM(unit_cost*quantity), 0) AS profit,
                branch_name
            ')
            ->groupBy('branch_id')
            ->orderByDesc('revenue')
            ->get();
    }

    public function getProfitByBrands()
    {
        return (clone $this->details)
            ->selectRaw('
                SUM(line_price_before_tax) AS revenue,
                SUM(unit_cost*quantity) AS total_cost,
                SUM(line_price_before_tax) - COALESCE(SUM(unit_cost*quantity), 0) AS profit,
                SUM(quantity) AS quantity,
                brand_name
            ')
            ->groupBy('brand_name')
            ->orderByDesc('revenue')
            ->get();
    }
}
