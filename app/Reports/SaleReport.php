<?php

namespace App\Reports;

use Illuminate\Support\Carbon;

class SaleReport
{
    private $period;

    private $branches;

    private $userId;

    private $master;

    private $customer;

    private $details;

    private $subtotalPrice;

    public function __construct($filters)
    {
        $source = ReportSource::getSalesReportInput($filters);

        $this->period = $filters['period'] ?? null;

        $this->branches = $filters['branches'] ?? null;

        $this->userId = $filters['user_id'] ?? null;

        $this->customerId = $filters['customer_id'] ?? null;

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

    public function getSalesCount()
    {
        return (clone $this->master)->count();
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

    public function getPaymentTypesByRevenue()
    {
        return (clone $this->master)->selectRaw('SUM(subtotal_price)*1.15 AS revenue, COUNT(payment_type) AS transactions, payment_type')->groupBy('payment_type')->orderByDesc('revenue')->get();
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

        return $cashPaymentTransactionCount / $this->getSalesCount * 100;
    }

    public function getLifetimeValue()
    {
        return (clone $this->master)->where('customer_id', $this->customer->id)->sum('subtotal_price');
    }

    public function getFavoriteCategory()
    {
        return (clone $this->details)
            ->where('customer_id', $this->customer->id)
            ->selectRaw('SUM(line_price)*1.15 AS revenue, SUM(quantity) AS quantity, product_category_name')
            ->groupBy('product_category_id')
            ->orderByDesc('revenue')
            ->get();
    }

    public function getFavoriteProducts()
    {
        return (clone $this->details)
            ->where('customer_id', $this->customer->id)
            ->selectRaw('SUM(line_price)*1.15 AS revenue, SUM(quantity) AS quantity, product_name, product_unit_of_measurement')
            ->groupBy('product_id')
            ->orderByDesc('revenue')
            ->get();
    }

    public function getFavoritePaymentMethod()
    {
        return (clone $this->master)
            ->selectRaw('COUNT(payment_type) AS payment_count, payment_type')
            ->where('customer_id', $this->customer->id)
            ->groupBy('payment_type')
            ->orderByDesc('payment_count')
            ->get();
    }

    public function getRevenueBySalesRep()
    {
        return (clone $this->master)
            ->where('customer_id', $this->customer->id)
            ->selectRaw('SUM(subtotal_price)*1.15 AS revenue, user_name')
            ->groupBy('created_by')->orderByDesc('revenue')
            ->get();
    }

    public function getRevenueByBranch()
    {
        return (clone $this->master)
            ->where('customer_id', $this->customer->id)
            ->selectRaw('SUM(subtotal_price)*1.15 AS revenue, warehouse_name')
            ->groupBy('warehouse_id')->orderByDesc('revenue')
            ->get();
    }

    public function getLastPurchaseDateAndValue()
    {
        return (clone $this->master)
            ->where('customer_id', $this->customer->id)
            ->selectRaw('(subtotal_price)*1.15 AS value, issued_on')
            ->latest('issued_on')
            ->first();
    }

    public function getTotalTransactions()
    {
        return (clone $this->master)
            ->where('customer_id', $this->customer->id)
            ->count();
    }

    public function getAverageTransactionValue()
    {
        $transactionCount = (clone $this->master)
            ->where('customer_id', $this->customer->id)
            ->count();

        if ($transactionCount == 0) {
            return $transactionCount;
        }

        return (clone $this->master)
            ->where('customer_id', $this->customer->id)
            ->sum('subtotal_price') * 1.15 / $transactionCount;
    }

    public function getPurchaseFrequency()
    {

    }
}