<?php

namespace App\Reports;

class ExpenseTransactionReport
{
    private $source;

    public function __construct($source)
    {
        $this->source = $source;

        $this->transactionCount = $this->source->count();
    }

    public function getAverageTransactionValue()
    {
        if ($this->transactionCount == 0) {
            return $this->transactionCount;
        }

        return $this->source->sum('grand_total_price_after_discount') / $this->transactionCount;
    }

    public function getAverageExpensePerTransaction()
    {
        if ($this->transactionCount == 0) {
            return $this->transactionCount;
        }

        return $this->source->pluck('details')->flatten(1)->count() / $this->transactionCount;
    }
}
