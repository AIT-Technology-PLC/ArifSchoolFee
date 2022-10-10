<?php

namespace App\Reports;

use Illuminate\Support\Carbon;

class SaleReport
{
    private $filters;

    private $master;

    private $details;

    private $subtotalPrice;

    public function __construct($filters)
    {
        $this->filters = $filters;

        $source = ReportSource::getSalesReportInput($filters);

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
        if (!isset($this->filters['period'])) {
            return 0;
        }

        $days = Carbon::parse($this->filters['period'][0])->diffInDays(Carbon::parse($this->filters['period'][1])) + 1;

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

    public function getRevenueBySalesRep()
    {
        return (clone $this->master)
            ->selectRaw('SUM(subtotal_price)*1.15 AS revenue, user_name')
            ->groupBy('created_by')->orderByDesc('revenue')
            ->get();
    }

    public function getRevenueByBranch()
    {
        return (clone $this->master)
            ->selectRaw('SUM(subtotal_price)*1.15 AS revenue, warehouse_name')
            ->groupBy('warehouse_id')->orderByDesc('revenue')
            ->get();
    }

    public function getLastPurchaseDateAndValue()
    {
        return (clone $this->master)
            ->selectRaw('(subtotal_price)*1.15 AS value, issued_on')
            ->latest('issued_on')
            ->first();
    }

    public function getCustomersBySalesTransactionsCount()
    {
        return (clone $this->master)
            ->selectRaw('COUNT(customer_name) AS transactions, customer_name')
            ->groupBy('customer_id')
            ->orderByDesc('transactions')
            ->get();
    }

    public function getCustomerByPaymentMethod()
    {
        return (clone $this->master)
            ->selectRaw('
                COUNT(CASE WHEN payment_type = "Cash Payment" THEN  payment_type END) AS cash_payment,
                COUNT(CASE WHEN payment_type = "Credit Payment" THEN  payment_type END) AS credit_payment,
                customer_name')
            ->groupBy('customer_id')
            ->orderByDesc('customer_name')
            ->get();
    }

    public function getCustomersByPurchaseFrequency()
    {
        return (clone $this->master)
        //  selectRaw query require some modification
            ->selectRaw('COUNT(issued_on) AS purchase_frequency, customer_name')
            ->groupBy('customer_id')
            ->orderByDesc('purchase_frequency')
            ->get();
    }

    public function getAverageRevenuePerCustomer()
    {
        $customers = (clone $this->master)->selectRaw('COUNT(DISTINCT customer_id) AS customer_count')->first()->customer_count;

        if ($customers == 0) {
            return $customers;
        }

        return $this->getTotalRevenueAfterTax / $customers;
    }

    public function getAverageSalesTransactionsPerCustomer()
    {
        $customers = (clone $this->master)->selectRaw('COUNT(DISTINCT customer_id) AS customer_count')->first()->customer_count;

        if ($customers == 0) {
            return $customers;
        }

        return (clone $this->master)->count() / $customers;
    }

    public function getTotalRetainedCustomers()
    {
        $customerAtBeginning = (clone $this->master)
            ->selectRaw('COUNT(DISTINCT customer_name) AS customer_at_beginning')
            ->whereDate('issued_on', '<=', $this->period[0])
            ->first()
            ->customer_at_beginning;

        $customerAtEnding = (clone $this->master)
            ->selectRaw('COUNT(DISTINCT customer_name) AS customer_at_ending')
            ->whereDate('issued_on', '<=', $this->period[1])
            ->first()
            ->customer_at_ending;

        $customerWithInPeriod = $customerAtBeginning - $customerAtEnding;

        if ($customerAtBeginning == 0) {
            return $customerAtBeginning;
        }

        return (($customerAtEnding - $customerWithInPeriod) / $customerAtBeginning) * 100;
    }

    public function getTotalChurnedCustomers()
    {
        return 100 - $this->getTotalRetainedCustomers;
    }
}
