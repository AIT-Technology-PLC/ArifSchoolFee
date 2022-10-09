<?php

namespace App\Reports;

use App\Models\Credit;
use App\Scopes\BranchScope;

class CustomerProfileReport
{
    private $query;

    private $branches;

    private $customer;

    private $period;

    public function __construct($branches, $period, $customer)
    {
        $this->branches = $branches;

        $this->customer = $customer;

        $this->period = $period;

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
        $this->query = Credit::query()
            ->whereIn('warehouse_id', $this->branches)
            ->whereDate('issued_on', '>=', $this->period[0])->whereDate('issued_on', '<=', $this->period[1])
            ->withoutGlobalScopes([BranchScope::class])
            ->leftJoin('credit_settlements', 'credit_settlements.credit_id', '=', 'credits.id');
    }

    public function getCreditAmout()
    {
        return (clone $this->query)
            ->selectRaw('SUM(credit_amount) AS credit_amount, SUM(credit_amount) - SUM(credit_amount_settled) AS unsettled_amount')
            ->where('customer_id', $this->customer->id)
            ->first();
    }

    public function getAverageCreditSettlementInDays()
    {
        return (clone $this->query)
            ->where('customer_id', $this->customer->id)
            ->selectRaw('SUM(DATEDIFF(last_settled_at, issued_on)) / COUNT(credits.id) as days')
            ->first()
            ->days;
    }
}