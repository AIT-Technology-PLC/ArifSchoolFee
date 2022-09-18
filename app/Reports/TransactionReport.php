<?php

namespace App\Reports;

class TransactionReport
{
    private $master;

    private $details;

    private $transactionCount;

    public function __construct($source)
    {
        $this->master = $source['master'];

        $this->details = $source['details'];

        $this->transactionCount = (clone $this->master)->count();
    }

    public function __get($name)
    {
        if (!isset($this->$name)) {
            $this->$name = $this->$name();
        }

        return $this->$name;
    }

    public function getAverageTransactionValue()
    {
        if ($this->transactionCount == 0) {
            return $this->transactionCount;
        }

        return (clone $this->master)->sum('subtotal_price') * 1.15 / $this->transactionCount;
    }

    public function getAverageItemsPerTransaction()
    {
        if ($this->transactionCount == 0) {
            return $this->transactionCount;
        }

        return $this->details->count() / $this->transactionCount;
    }
}