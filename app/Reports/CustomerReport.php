<?php

namespace App\Reports;

use App\Models\Customer;

class CustomerReport
{
    private $query;

    private $period;

    public function __construct($period)
    {
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
        $this->query = Customer::query();
    }

    public function getTotalCustomers()
    {
        return (clone $this->query)->count();
    }

    public function getTotalNewCustomers()
    {
        return (clone $this->query)
            ->whereDate('created_at', '>=', $this->period[0])->whereDate('created_at', '<=', $this->period[1])
            ->count();
    }
}
