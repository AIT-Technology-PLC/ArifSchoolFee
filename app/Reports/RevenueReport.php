<?php

namespace App\Reports;

class RevenueReport
{
    private $source;

    public function __construct($source)
    {
        $this->source = $source;
    }

    public function getTotalRevenueBeforeTax()
    {
        return $this->source->sum('subtotal_price');
    }

    public function getTotalRevenueAfterTax()
    {
        return $this->source->sum('grand_total_price_after_discount');
    }

    public function getTotalRevenueTax()
    {
        return $this->source->sum('tax_amount');
    }

    public function getBranchesByRevenue()
    {
        $branchesByRevenue = collect();

        foreach ($this->source->unique('branch_name') as $value) {
            $branchesByRevenue->push([
                'branch' => $value['branch_name'],
                'revenue' => $this->source->where('branch_name', $value['branch_name'])->sum('grand_total_price_after_discount'),
            ]);
        }

        return $branchesByRevenue->sortByDesc('revenue');
    }

    public function getCustomersByRevenue()
    {
        $customerByRevenue = collect();

        foreach ($this->source->unique('customer_name') as $value) {
            $customerByRevenue->push([
                'customer' => $value['customer_name'],
                'revenue' => $this->source->where('customer_name', $value['customer_name'])->sum('grand_total_price_after_discount'),
            ]);
        }

        return $customerByRevenue->sortByDesc('revenue');
    }

    public function getRepsByRevenue()
    {
        $repsByRevenue = collect();

        foreach ($this->source->unique('sales_rep_name') as $value) {
            $repsByRevenue->push([
                'sales' => $value['sales_rep_name'],
                'revenue' => $this->source->where('sales_rep_name', $value['sales_rep_name'])->sum('grand_total_price_after_discount'),
            ]);
        }

        return $repsByRevenue->sortByDesc('revenue');
    }

    public function getProductsByRevenue()
    {
        $productsByRevenue = collect();

        foreach ($this->source->pluck('details')->flatten(1)->unique('product_name') as $value) {
            $productsByRevenue->push([
                'product' => $value['product_name'],
                'quantity' => $this->source->pluck('details')->flatten(1)->where('product_name', $value['product_name'])->sum('quantity') . ' ' . $value['unit_of_measurement'],
                'revenue' => $this->source->pluck('details')->flatten(1)->where('product_name', $value['product_name'])->sum('unit_price'),
            ]);
        }

        return $productsByRevenue->sortByDesc('revenue');
    }

    public function getProductCategoriesByRevenue()
    {
        $productCategoriesByRevenue = collect();

        foreach ($this->source->pluck('details')->flatten(1)->unique('product_category_name') as $value) {
            $productCategoriesByRevenue->push([
                'category' => $value['product_category_name'],
                'quantity' => $this->source->pluck('details')->flatten(1)->where('product_category_name', $value['product_category_name'])->sum('quantity') . ' ' . $value['unit_of_measurement'],
                'revenue' => $this->source->pluck('details')->flatten(1)->where('product_category_name', $value['product_category_name'])->sum('unit_price'),
            ]);
        }

        return $productCategoriesByRevenue->sortByDesc('revenue');
    }

    public function getTotalRevenueReveivables()
    {
        return $this->source->sum('credit_amount');
    }

    public function getDailyAverageRevenue()
    {
        if ($this->source->unique('transaction_date')->count() == 0) {
            return 0;
        }

        return $this->source->sum('grand_total_price_after_discount') / $this->source->unique('transaction_date')->count();
    }
}
