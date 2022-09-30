<?php

namespace App\Reports;

use Illuminate\Support\Carbon;

class SaleReport
{
    private $period;

    private $branches;

    private $employee;

    private $master;

    private $details;

    private $subtotalPrice;

    public function __construct($branches, $period, $employee)
    {
        $source = ReportSource::getSalesReportInput($branches, $period, $employee);

        $this->period = $period;

        $this->branches = $branches;

        $this->employee = $employee;

        $this->master = $source['master'];

        $this->details = $source['details'];

        $this->subtotalPrice = (clone $this->master)->sum('subtotal_price');
    }

    public function __get($name)
    {
        if (!isset($this->$name)) {
            $this->$name = $this->$name();
        }

        return $this->$name;
    }

    public function getTotalRevenueBeforeTax()
    {
        return $this->subtotalPrice;
    }

    public function getTotalRevenueAfterTax()
    {
        return $this->subtotalPrice * 1.15;
    }

    public function getTotalRevenueTax()
    {
        return $this->subtotalPrice * 0.15;
    }

    public function getBranchesByRevenue()
    {
        return (clone $this->master)->selectRaw('SUM(subtotal_price)*1.15 AS revenue, warehouse_name')->groupBy('warehouse_id')->orderByDesc('revenue')->get();
    }

    public function getCustomersByRevenue()
    {
        return (clone $this->master)->selectRaw('SUM(subtotal_price)*1.15 AS revenue, customer_name')->groupBy('customer_id')->orderByDesc('revenue')->get();
    }

    public function getRepsByRevenue()
    {
        return (clone $this->master)->selectRaw('SUM(subtotal_price)*1.15 AS revenue, user_name')->groupBy('created_by')->orderByDesc('revenue')->get();
    }

    public function getProductsByRevenue()
    {
        return (clone $this->details)->selectRaw('SUM(line_price)*1.15 AS revenue, SUM(quantity) AS quantity, product_name, product_unit_of_measurement')->groupBy('product_id')->orderByDesc('revenue')->get();
    }

    public function getProductCategoriesByRevenue()
    {
        return (clone $this->details)->selectRaw('SUM(line_price)*1.15 AS revenue, SUM(quantity) AS quantity, product_category_name')->groupBy('product_category_id')->orderByDesc('revenue')->get();
    }

    public function getDailyAverageRevenue()
    {
        $days = Carbon::parse($this->period[0])->diffInDays(Carbon::parse($this->period[1])) + 1;

        return $this->getTotalRevenueAfterTax / $days;
    }

    public function getCashSalesPercentage()
    {
        $cashPaymentTransactionCount = (clone $this->master)->where('payment_type', 'Cash Payment')->count();

        if ($cashPaymentTransactionCount == 0) {
            return $cashPaymentTransactionCount;
        }

        return $cashPaymentTransactionCount / (clone $this->master)->count() * 100;
    }
}