<?php

namespace App\Reports;

class SalesReturnReport
{
    private $source;

    public function __construct($branches, $period)
    {
        $this->source = ReportSource::getSalesReturnReportInput($branches, $period);
    }

    public function __get($name)
    {
        if (!isset($this->$name)) {
            $this->$name = $this->$name();
        }

        return $this->$name;
    }

    public function getReturnsCount()
    {
        return (clone $this->source)->count();
    }

    public function getTotalRevenueBeforeTax()
    {
        return (clone $this->source)
            ->selectRaw('SUM(quantity*unit_price) AS revenue')
            ->first()->revenue;
    }

    public function getTotalRevenueAfterTax()
    {
        return $this->getTotalRevenueBeforeTax * 1.15;
    }

    public function getTotalRevenueTax()
    {
        return $this->getTotalRevenueBeforeTax * 0.15;
    }

    public function getCustomersCount()
    {
        return (clone $this->source)
            ->selectRaw('customers.company_name AS customer_name')
            ->groupBy('customer_name')
            ->having('customer_name', '<>', '')
            ->count();
    }

    public function getReturnsByProducts()
    {
        return (clone $this->source)
            ->selectRaw('products.name AS product_name, SUM(quantity) AS quantity, SUM(quantity*unit_price) AS revenue')
            ->groupBy('product_name')
            ->orderByDesc('revenue')
            ->get();
    }

    public function getReturnsByCustomers()
    {
        return (clone $this->source)
            ->selectRaw('customers.company_name AS customer_name, SUM(quantity*unit_price) AS revenue, COUNT(return_id) AS returns')
            ->groupBy('customer_name')
            ->orderByDesc('revenue')
            ->get();
    }

    public function getReturnsByBranches()
    {
        return (clone $this->source)
            ->selectRaw('warehouses.name AS branch_name, SUM(quantity*unit_price) AS revenue, COUNT(return_id) AS returns')
            ->groupBy('branch_name')
            ->orderByDesc('revenue')
            ->get();
    }
}
