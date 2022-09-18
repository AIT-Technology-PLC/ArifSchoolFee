<?php

namespace App\Reports;

use Illuminate\Support\Carbon;

class ExpenseReport
{
    private $source;

    private $period;

    public function __construct($branches, $period)
    {
        $this->source = ReportSource::getExpenseReportInput($branches, $period);

        $this->period = $period;
    }

    public function getTotalExpenseBeforeTax()
    {
        return $this->source->sum('subtotal_price');
    }

    public function getTotalExpenseAfterTax()
    {
        return $this->source->sum('grand_total_price_after_discount');
    }

    public function getTotalExpenseTax()
    {
        return $this->source->sum('tax_amount');
    }

    public function getBranchesByExpense()
    {
        $branchesByExpense = collect();

        foreach ($this->source->unique('branch_name') as $value) {
            $branchesByExpense->push([
                'branch' => $value['branch_name'],
                'expense' => $this->source->where('branch_name', $value['branch_name'])->sum('grand_total_price_after_discount'),
            ]);
        }

        return $branchesByExpense->sortByDesc('expense');
    }

    public function getSuppliersByExpense()
    {
        $suppliersByExpense = collect();

        foreach ($this->source->unique('supplier_name') as $value) {
            $suppliersByExpense->push([
                'supplier' => $value['supplier_name'],
                'expense' => $this->source->where('supplier_name', $value['supplier_name'])->sum('grand_total_price_after_discount'),
            ]);
        }

        return $suppliersByExpense->sortByDesc('expense');
    }

    public function getPurchaserByExpense()
    {
        $purchaserByExpense = collect();

        foreach ($this->source->unique('purchaser_name') as $value) {
            $purchaserByExpense->push([
                'purchaser' => $value['purchaser_name'],
                'expense' => $this->source->where('purchaser_name', $value['purchaser_name'])->sum('grand_total_price_after_discount'),
            ]);
        }

        return $purchaserByExpense->sortByDesc('expense');
    }

    public function getExpenseCategoriesByExpense()
    {
        $expenseCategoriesByExpense = collect();

        foreach ($this->source->pluck('details')->flatten(1)->unique('expense_category_name') as $value) {
            $expenseCategoriesByExpense->push([
                'category' => $value['expense_category_name'],
                'quantity' => $this->source->pluck('details')->flatten(1)->where('expense_category_name', $value['expense_category_name'])->sum('quantity'),
                'expense' => $this->source->pluck('details')->flatten(1)->where('expense_category_name', $value['expense_category_name'])->reduce(function ($carry, $item) {
                    return $carry + ($item['unit_price'] * $item['quantity']);
                }),
            ]);
        }

        return $expenseCategoriesByExpense->sortByDesc('expense');
    }

    public function getDailyAverageExpense()
    {
        $days = Carbon::parse($this->period[0])->diffInDays(Carbon::parse($this->period[1])) + 1;

        return $this->getTotalExpenseAfterTax() / $days;
    }
}
