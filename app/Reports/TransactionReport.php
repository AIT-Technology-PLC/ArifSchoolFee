<?php

namespace App\Reports;

class TransactionReport
{
    private $source;

    public function __construct($source)
    {
        $this->source = $source;
    }

    public function getAverageTransactionValue()
    {
        if ($this->source->count() == 0) {
            return 0;
        }

        return $this->source->sum('grand_total_price_after_discount') / $this->source->count();
    }

    public function getTotalTransactionCount()
    {
        return $this->source->count();
    }

    public function getAverageItemsPerTransaction()
    {
        if ($this->source->count() == 0) {
            return 0;
        }

        return $this->source->pluck('details')->flatten(1)->count() / $this->source->count();
    }
}
