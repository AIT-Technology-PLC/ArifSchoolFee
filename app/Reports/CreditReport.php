<?php

namespace App\Reports;

use App\Models\Credit;
use App\Models\CreditSettlement;

class CreditReport
{
    private $creditQuery;

    private $creditSettlementQuery;

    private $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;

        $this->setCreditQuery();

        $this->setCreditSettlementQuery();
    }

    public function __get($name)
    {
        if (!isset($this->$name)) {
            $this->$name = $this->$name();
        }

        return $this->$name;
    }

    private function setCreditQuery()
    {
        $this->creditQuery = Credit::query()
            ->withoutGlobalScopes([BranchScope::class])
            ->join('warehouses', 'credits.warehouse_id', '=', 'warehouses.id')
            ->join('customers', 'credits.customer_id', '=', 'customers.id')
            ->when(isset($this->filters['period']), fn($q) => $q->whereDate('credits.issued_on', '>=', $this->filters['period'][0])->whereDate('credits.issued_on', '<=', $this->filters['period'][1]))
            ->when(isset($this->filters['branches']), fn($q) => $q->whereIn('credits.warehouse_id', $this->filters['branches']));
    }

    private function setCreditSettlementQuery()
    {
        $this->creditSettlementQuery = CreditSettlement::query()
            ->whereHas('credit', fn($q) => $q->withoutGlobalScopes([BranchScope::class]))
            ->join('credits', 'credit_settlements.credit_id', '=', 'credits.id')
            ->join('customers', 'credits.customer_id', '=', 'customers.id')
            ->join('warehouses', 'credits.warehouse_id', '=', 'warehouses.id')
            ->when(isset($this->filters['period']), fn($q) => $q->whereDate('credit_settlements.settled_at', '>=', $this->filters['period'][0])->whereDate('credit_settlements.settled_at', '<=', $this->filters['period'][1]))
            ->when(isset($this->filters['branches']), fn($q) => $q->whereIn('credits.warehouse_id', $this->filters['branches']));
    }

    public function getTotalCreditGiven()
    {
        return (clone $this->creditQuery)->count();
    }

    public function getTotalCustomersReceivedCredit()
    {
        return (clone $this->creditQuery)->distinct('credits.customer_id')->count();
    }

    public function getTotalCreditAmount()
    {
        return (clone $this->creditQuery)->sum('credits.credit_amount');
    }

    public function getTotalCreditByCustomer()
    {
        return (clone $this->creditQuery)
            ->selectRaw('SUM(credits.credit_amount) AS credit_amount, COUNT(credits.id) AS transactions, customers.company_name AS customer_name')
            ->groupBy('customer_name')
            ->orderByDesc('credits.credit_amount')
            ->get();
    }

    public function getTotalCreditByBranch()
    {
        return (clone $this->creditQuery)
            ->selectRaw('SUM(credits.credit_amount) AS credit_amount, COUNT(credits.id) AS transactions, warehouses.name AS warehouse_name')
            ->groupBy('warehouse_name')
            ->orderByDesc('credits.credit_amount')
            ->get();
    }

    public function getTotalSettlementMade()
    {
        return (clone $this->creditSettlementQuery)->count();
    }

    public function getTotalCustomerMadeSettlement()
    {
        return (clone $this->creditSettlementQuery)->distinct('credits.customer_id')->count();
    }

    public function getTotalSettledAmount()
    {
        return (clone $this->creditSettlementQuery)->sum('credit_settlements.amount');
    }

    public function getSettlmentByCustomer()
    {
        return (clone $this->creditSettlementQuery)
            ->selectRaw('SUM(credit_settlements.amount) AS credit_amount_settled, COUNT(credit_settlements.id) AS transactions, customers.company_name AS customer_name')
            ->groupBy('customer_name')
            ->orderByDesc('credit_settlements.amount')
            ->get();
    }

    public function getSettlmentByBranch()
    {
        return (clone $this->creditSettlementQuery)
            ->selectRaw('SUM(credit_settlements.amount) AS credit_amount_settled, COUNT(credit_settlements.id) AS transactions, warehouses.name AS warehouse_name')
            ->groupBy('warehouse_name')
            ->orderByDesc('credit_settlements.amount')
            ->get();
    }
}
