<?php

namespace App\Reports;

use App\Models\ReturnDetail;
use App\Scopes\BranchScope;

class ReturnReport
{
    private $query;

    private $branches;

    private $period;

    public function __construct($filters)
    {
        $this->branches = $filters['branches'] ?? null;

        $this->period = $filters['period'] ?? null;

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
        $this->query = ReturnDetail::query()
            ->whereHas('returnn', function ($q) {
                return $q->whereIn('warehouse_id', $this->branches)
                    ->whereDate('issued_on', '>=', $this->period[0])->whereDate('issued_on', '<=', $this->period[1])
                    ->added()
                    ->withoutGlobalScopes([BranchScope::class]);
            })
            ->join('products', 'return_details.product_id', '=', 'products.id')
            ->join('taxes', 'products.tax_id', '=', 'taxes.id')
            ->join('returns', 'return_details.return_id', '=', 'returns.id')
            ->join('warehouses', 'returns.warehouse_id', '=', 'warehouses.id')
            ->leftJoin('gdns', 'returns.gdn_id', '=', 'gdns.id')
            ->leftJoin('customers', function ($join) {
                $join->on('returns.customer_id', '=', 'customers.id')
                    ->orOn('gdns.customer_id', '=', 'customers.id');
            });
    }

    public function getReturnsCount()
    {
        return (clone $this->query)->distinct('return_id')->count();
    }

    public function getTotalRevenueBeforeTax()
    {
        return (clone $this->query)
            ->selectRaw('SUM(quantity*unit_price) AS revenue')
            ->first()
            ->revenue;
    }

    public function getTotalRevenueAfterTax()
    {
        return (clone $this->query)
            ->selectRaw('SUM(quantity*unit_price*(1+taxes.amount)) AS revenue')
            ->first()
            ->revenue;
    }

    public function getTotalRevenueTax()
    {
        return (clone $this->query)
            ->selectRaw('SUM(quantity*unit_price*taxes.amount) AS revenue')
            ->first()
            ->revenue;
    }

    public function getCustomersCount()
    {
        return (clone $this->query)
            ->selectRaw('customers.company_name AS customer_name')
            ->distinct('customer_name')
            ->having('customer_name', '<>', '')
            ->count();
    }

    public function getReturnsByProducts()
    {
        return (clone $this->query)
            ->selectRaw('products.name AS product_name, SUM(quantity) AS quantity, SUM(quantity*unit_price*(1+taxes.amount)) AS revenue')
            ->groupBy('product_name')
            ->orderByDesc('revenue')
            ->get();
    }

    public function getReturnsByCustomers()
    {
        return (clone $this->query)
            ->selectRaw('customers.company_name AS customer_name, SUM(quantity*unit_price*(1+taxes.amount)) AS revenue, COUNT(DISTINCT return_id) AS returns')
            ->groupBy('customer_name')
            ->orderByDesc('revenue')
            ->get();
    }

    public function getReturnsByBranches()
    {
        return (clone $this->query)
            ->selectRaw('warehouses.name AS branch_name, SUM(quantity*unit_price*(1+taxes.amount)) AS revenue, COUNT(DISTINCT return_id) AS returns')
            ->groupBy('branch_name')
            ->orderByDesc('revenue')
            ->get();
    }
}
