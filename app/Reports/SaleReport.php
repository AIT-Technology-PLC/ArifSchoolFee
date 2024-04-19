<?php

namespace App\Reports;

use Illuminate\Support\Carbon;

class SaleReport
{
    private $filters;

    private $master;

    private $details;

    private $base;

    public function __construct($filters)
    {
        $this->filters = $filters;

        $source = ReportSource::getSalesReportInput($filters);

        $this->master = $source['master'];

        $this->details = $source['details'];

        $this->base = $source['base'];
    }

    public function __get($name)
    {
        if (!isset($this->$name)) {
            $this->$name = $this->$name();
        }

        return $this->$name;
    }

    public function getSalesCount()
    {
        return (clone $this->details)->distinct('master_id')->count();
    }

    public function getAverageSaleValue()
    {
        if ($this->getSalesCount == 0) {
            return $this->getSalesCount;
        }

        return $this->getTotalRevenueAfterTax / $this->getSalesCount;
    }

    public function getAverageItemsPerSale()
    {
        if ($this->getSalesCount == 0) {
            return $this->getSalesCount;
        }

        return (clone $this->details)->count() / $this->getSalesCount;
    }

    public function subtotalPrice()
    {
        return (clone $this->details)->sum('line_price_before_tax');
    }

    public function getTotalRevenueBeforeTax()
    {
        return $this->subtotalPrice;
    }

    public function getTotalRevenueAfterTax()
    {
        return $this->subtotalPrice + $this->getTotalRevenueTax;
    }

    public function getTotalRevenueTax()
    {
        return (clone $this->details)->sum('line_tax');
    }

    public function getSalesDetails()
    {
        return (clone $this->details)->orderBy('issued_on')->get();
    }

    public function getBranchesByRevenue()
    {
        return (clone $this->details)
            ->selectRaw('SUM(line_price_before_tax+line_tax) AS revenue, branch_name, branch_id')
            ->when(isset($this->filters['product_id']), fn($q) => $q->selectRaw('SUM(quantity) AS quantity, product_unit_of_measurement'))
            ->groupBy('branch_id')
            ->orderByDesc('revenue')
            ->get();
    }

    public function getCustomersByRevenue()
    {
        return (clone $this->details)
            ->selectRaw('SUM(line_price_before_tax+line_tax) AS revenue, customer_name, customer_id')
            ->when(isset($this->filters['product_id']), fn($q) => $q->selectRaw('SUM(quantity) AS quantity, product_unit_of_measurement'))
            ->groupBy('customer_id')
            ->orderByDesc('revenue')
            ->get();
    }

    public function getRepsByRevenue()
    {
        return (clone $this->details)
            ->selectRaw('SUM(line_price_before_tax+line_tax) AS revenue, user_name, created_by')
            ->when(isset($this->filters['product_id']), fn($q) => $q->selectRaw('SUM(quantity) AS quantity, product_unit_of_measurement'))
            ->groupBy('created_by')
            ->orderByDesc('revenue')
            ->get();
    }

    public function getProductsByRevenue()
    {
        return (clone $this->details)
            ->selectRaw('SUM(line_price_before_tax+line_tax) AS revenue, SUM(quantity) AS quantity, product_name, product_unit_of_measurement')
            ->groupBy('product_id')
            ->orderByDesc('revenue')
            ->get();
    }

    public function getProductCategoriesByRevenue()
    {
        return (clone $this->details)
            ->selectRaw('SUM(line_price_before_tax+line_tax) AS revenue, SUM(quantity) AS quantity, product_category_name')
            ->groupBy('product_category_id')
            ->orderByDesc('revenue')
            ->get();
    }

    public function getBrandsByRevenue()
    {
        return (clone $this->details)
            ->selectRaw('SUM(line_price_before_tax+line_tax) AS revenue, brand_name')
            ->groupBy('brand_name')
            ->orderByDesc('revenue')
            ->get();
    }

    public function getPaymentTypesByRevenue()
    {
        return (clone $this->details)
            ->selectRaw('SUM(line_price_before_tax+line_tax) AS revenue, COUNT(DISTINCT master_id) AS transactions, payment_type')
            ->groupBy('payment_type')
            ->orderByDesc('revenue')
            ->get();
    }

    public function getDailyAverageRevenue()
    {
        if (!isset($this->filters['period'])) {
            return 0;
        }

        $days = Carbon::parse($this->filters['period'][0])->diffInDays(Carbon::parse($this->filters['period'][1])) + 1;

        return $this->getTotalRevenueAfterTax / $days;
    }

    public function getCashSalesPercentage()
    {
        $cashPaymentTransactionCount = (clone $this->details)->where('payment_type', 'Cash Payment')->distinct('master_id')->count();

        if ($cashPaymentTransactionCount == 0) {
            return $cashPaymentTransactionCount;
        }

        return $cashPaymentTransactionCount / $this->getSalesCount * 100;
    }

    public function getUnitPricesWhere($column, $value)
    {
        return (clone $this->details)
            ->where($column, $value)
            ->selectRaw('(line_price_before_tax+line_tax)/quantity as unit_price')
            ->pluck('unit_price')
            ->toArray();
    }

    public function getSalesByPaymentMethods()
    {
        return (clone $this->details)
            ->selectRaw('
                SUM(line_price_before_tax+line_tax) AS amount,
                code,
                issued_on,
                last_settled_at,
                SUM(credit_amount) AS credit_amount,
                SUM(credit_amount_settled) AS credit_amount_settled,
                SUM(credit_amount_unsettled) AS credit_amount_unsettled,
                customer_name,
                payment_type,
                bank_name,
                reference_number
            ')
            ->groupBy('code')
            ->orderBy('issued_on')
            ->get();
    }
}
