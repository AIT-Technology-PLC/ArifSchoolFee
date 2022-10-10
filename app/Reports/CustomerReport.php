<?php

namespace App\Reports;

use App\Models\Customer;

class CustomerReport
{
    private $filters;

    private $saleReport;

    public function __construct($filters)
    {
        $this->filters = $filters;

        $this->saleReport = new SaleReport($filters);
    }

    public function __get($name)
    {
        if (!isset($this->$name)) {
            $this->$name = $this->$name();
        }

        return $this->$name;
    }

    public function getTotalCustomers()
    {
        return Customer::count();
    }

    public function getTotalActiveCustomers()
    {
        return (clone $this->saleReport->base)
            ->distinct('customer_name')
            ->count();
    }

    public function getTotalInactiveCustomers()
    {
        return $this->getTotalCustomers - $this->getTotalActiveCustomers;
    }

    public function getTotalNewCustomers()
    {
        return (clone $this->saleReport->master)
            ->whereDate('customer_created_at', '>=', $this->filters['period'][0])
            ->whereDate('customer_created_at', '<=', $this->filters['period'][1])
            ->distinct('customer_name')
            ->count();
    }

    public function getTotalCustomersAtPeriodBeginning()
    {
        return (clone $this->saleReport->base)
            ->when(isset($this->filters['branches']), fn($q) => $q->whereIn('warehouse_id', $this->filters['branches']))
            ->whereDate('customer_created_at', '<', $this->filters['period'][0])
            ->distinct('customer_name')
            ->count();
    }

    public function getTotalCustomerWithinPeriod()
    {
        return (clone $this->saleReport->master)->distinct('customer_name')->count();
    }

    public function getTotalRetainedCustomers()
    {
        return [
            'amount' => $this->getTotalCustomerWithinPeriod - $this->getTotalNewCustomers,
            'percent' => (($this->getTotalCustomerWithinPeriod - $this->getTotalNewCustomers) / ($this->getTotalCustomersAtPeriodBeginning ?: 1)) * 100,
        ];
    }

    public function getTotalChurnedCustomers()
    {
        return [
            'amount' => $this->getTotalCustomersAtPeriodBeginning - $this->getTotalRetainedCustomers['amount'],
            'percent' => 100 - $this->getTotalRetainedCustomers['percent'],
        ];
    }

    public function getCustomersBySalesTransactionsCount()
    {
        return (clone $this->saleReport->master)
            ->selectRaw('COUNT(customer_name) AS transactions, customer_name')
            ->groupBy('customer_id')
            ->orderByDesc('transactions')
            ->get();
    }

    public function getCustomersByPaymentMethod()
    {
        return (clone $this->saleReport->master)
            ->selectRaw('
                COUNT(CASE WHEN payment_type = "Cash Payment" THEN  payment_type END) AS cash_payment,
                COUNT(CASE WHEN payment_type = "Credit Payment" THEN  payment_type END) AS credit_payment,
                COUNT(CASE WHEN payment_type = "Bank Deposit" THEN  payment_type END) AS bank_deposit,
                COUNT(CASE WHEN payment_type = "Bank Transfer" THEN  payment_type END) AS bank_transfer,
                COUNT(CASE WHEN payment_type = "Cheque" THEN  payment_type END) AS cheque,
                customer_name')
            ->groupBy('customer_id')
            ->orderByDesc('cash_payment')
            ->get();
    }

    public function getAverageRevenuePerCustomer()
    {
        $customers = $this->getTotalCustomerWithinPeriod;

        if ($customers == 0) {
            return $customers;
        }

        return $this->saleReport->getTotalRevenueAfterTax / $customers;
    }

    public function getAverageSalesTransactionsPerCustomer()
    {
        $customers = $this->getTotalCustomerWithinPeriod;

        if ($customers == 0) {
            return $customers;
        }

        return (clone $this->saleReport->master)->count() / $customers;
    }
}
