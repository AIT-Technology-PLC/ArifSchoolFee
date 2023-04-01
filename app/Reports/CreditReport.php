<?php

namespace App\Reports;

use App\Models\Credit;

class CreditReport
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
        $this->query = Credit::query()->withoutGlobalScopes([BranchScope::class])
            ->join('warehouses', 'credits.warehouse_id', '=', 'warehouses.id')
            ->leftJoin('credit_settlements', 'credits.id', '=', 'credit_settlements.credit_id')
            ->leftJoin('customers', 'credits.customer_id', '=', 'customers.id')
            ->when(isset($this->filters['branches']), fn($q) => $q->whereIn('credits.warehouse_id', $this->filters['branches']));
    }

    public function getTotalCreditGiven()
    {
        return (clone $this->query)
            ->when(isset($this->filters['period']), $this->getDate('credits.issued_on'))
            ->count();
    }

    public function getTotalCustomersReceivedCredit()
    {
        return (clone $this->query)->distinct('customer_id')
            ->when(isset($this->filters['period']), $this->getDate('credits.issued_on'))
            ->count();
    }

    public function getTotalSettlementMade()
    {
        return (clone $this->query)
            ->when(isset($this->filters['period']), $this->getDate('credit_settlements.settled_at'))
            ->count('credit_id');
    }

    public function getTotalCustomerMadeSettlement()
    {
        return (clone $this->query)
            ->when(isset($this->filters['period']), $this->getDate('credit_settlements.settled_at'))
            ->distinct('customer_id')->count('credit_id');
    }

    public function getTotalCreditByCustomer()
    {
        return (clone $this->query)
            ->when(isset($this->filters['period']), $this->getDate('credits.issued_on'))
            ->selectRaw('
                SUM(credit_amount
                ) AS credit_amount,
                customers.company_name AS customer_name
            ')
            ->groupBy('customer_name')
            ->orderByDesc('credit_amount')
            ->get();
    }

    public function getSettlmentByCustomer()
    {
        return (clone $this->query)
            ->when(isset($this->filters['period']), $this->getDate('credit_settlements.settled_at'))
            ->selectRaw('
                SUM(credit_settlements.amount
                ) AS credit_amount_settled,
                customers.company_name AS customer_name
            ')
            ->groupBy('customer_name')
            ->orderByDesc('credit_amount_settled')
            ->get();
    }

    public function getDate($date)
    {
        return fn($q) => $q->whereDate($date, '>=', $this->filters['period'][0])->whereDate($date, '<=', $this->filters['period'][1]);

    }
}