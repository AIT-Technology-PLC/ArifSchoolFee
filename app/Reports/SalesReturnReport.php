<?php

namespace App\Reports;

class SalesReturnReport
{
    private $source;

    public function __construct($source)
    {
        $this->source = $source;
    }

    public function getReturnTransactionCount()
    {
        return $this->source->count();
    }

    public function getMostReturnedProducts()
    {
        $mostReturnedProducts = collect();

        foreach ($this->source->pluck('details')->flatten(1)->unique('product_name') as $value) {
            $mostReturnedProducts->push([
                'product' => $value['product_name'],
                'quantity' => $this->source->pluck('details')->flatten(1)->where('product_name', $value['product_name'])->sum('quantity'),
            ]);
        }

        return $mostReturnedProducts->sortByDesc('quantity');
    }

    public function getHighestReturningCustomers()
    {
        $highestReturningCustomer = collect();

        foreach ($this->source->unique('customer_name') as $value) {
            $highestReturningCustomer->push([
                'customer' => $value['customer_name'],
                'quantity' => $this->source->where('customer_name', $value['customer_name'])->pluck('details')->flatten(1)->sum('quantity'),
            ]);
        }

        return $highestReturningCustomer->sortByDesc('quantity');
    }
}