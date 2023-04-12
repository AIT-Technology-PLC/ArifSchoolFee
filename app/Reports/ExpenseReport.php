<?php

namespace App\Reports;

use App\Models\ExpenseDetail;
use App\Scopes\BranchScope;
use Illuminate\Support\Carbon;

class ExpenseReport
{
    private $query;

    private $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;

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
        $this->query = ExpenseDetail::query()
            ->whereHas('expense', fn($q) => $q->approved()->withoutGlobalScopes([BranchScope::class]))
            ->join('expense_categories', 'expense_details.expense_category_id', '=', 'expense_categories.id')
            ->join('expenses', 'expense_details.expense_id', '=', 'expenses.id')
            ->join('warehouses', 'expenses.warehouse_id', '=', 'warehouses.id')
            ->join('taxes', 'expenses.tax_id', '=', 'taxes.id')
            ->leftJoin('users', 'expenses.created_by', '=', 'users.id')
            ->leftJoin('suppliers', 'expenses.supplier_id', '=', 'suppliers.id')
            ->when(isset($this->filters['branches']), fn($q) => $q->whereIn('expenses.warehouse_id', $this->filters['branches']))
            ->when(isset($this->filters['period']), fn($q) => $q->whereDate('expenses.issued_on', '>=', $this->filters['period'][0])->whereDate('expenses.issued_on', '<=', $this->filters['period'][1]))
            ->when(isset($this->filters['supplier_id']), fn($query) => $query->where('expenses.supplier_id', $this->filters['supplier_id']))
            ->when(isset($this->filters['tax_id']), fn($query) => $query->where('expenses.tax_id', $this->filters['tax_id']))
            ->when(isset($this->filters['expense_category_id']), fn($query) => $query->where('expense_details.expense_category_id', $this->filters['expense_category_id']));
    }

    public function getExpenseTransactionCount()
    {
        return (clone $this->query)->distinct('expense_id')->count();
    }

    public function getTotalExpenseBeforeTax()
    {
        return (clone $this->query)
            ->selectRaw('SUM(quantity*unit_price) AS expense_before_tax')
            ->first()
            ->expense_before_tax;
    }

    public function getTotalExpenseAfterTax()
    {
        return (clone $this->query)
            ->selectRaw('
                SUM(quantity*unit_price*(1+taxes.amount)
                ) AS expense_after_tax
            ')
            ->first()
            ->expense_after_tax;
    }

    public function getTotalExpenseVat()
    {
        return (clone $this->query)
            ->selectRaw('SUM(quantity*unit_price*taxes.amount) AS expense_vat')
            ->where('taxes.type', 'VAT')
            ->first()
            ->expense_vat;
    }

    public function getTotalExpenseTot()
    {
        return (clone $this->query)
            ->selectRaw('SUM(quantity*unit_price*taxes.amount) AS expense_tot')
            ->where('taxes.type', 'TOT2')
            ->orWhere('taxes.type', 'TOT5')
            ->orWhere('taxes.type', 'TOT10')
            ->first()
            ->expense_tot;
    }

    public function getExpenseByBranches()
    {
        return (clone $this->query)
            ->selectRaw('
                SUM(quantity*unit_price*(1+taxes.amount)) AS expense,
                warehouses.name AS branch_name
            ')
            ->groupBy('branch_name')
            ->orderByDesc('expense')
            ->get();
    }

    public function getExpenseBySuppliers()
    {
        return (clone $this->query)
            ->selectRaw('
                SUM(quantity*unit_price*(1+taxes.amount)) AS expense,
                suppliers.company_name AS supplier_name
            ')
            ->groupBy('supplier_name')
            ->orderByDesc('expense')
            ->get();
    }

    public function getExpenseByPurchasers()
    {
        return (clone $this->query)
            ->selectRaw('
                SUM(quantity*unit_price*(1+taxes.amount)) AS expense,
                users.name AS purchaser_name
            ')
            ->groupBy('purchaser_name')
            ->orderByDesc('expense')
            ->get();
    }

    public function getExpenseByCategories()
    {
        return (clone $this->query)
            ->selectRaw('
                SUM(quantity*unit_price*(1+taxes.amount)) AS expense,
                expense_categories.name AS category_name,
                SUM(quantity) AS quantity
            ')
            ->groupBy('category_name')
            ->orderByDesc('expense')
            ->get();
    }

    public function getExpenseByItems()
    {
        return (clone $this->query)
            ->selectRaw('
                SUM(quantity*unit_price*(1+taxes.amount)) AS expense,
                expense_details.name AS name,
                SUM(quantity) AS quantity
            ')
            ->groupBy('name')
            ->orderByDesc('expense')
            ->get();
    }

    public function getDailyAverageExpense()
    {
        if (!isset($this->filters['period'])) {
            return 0;
        }

        $days = Carbon::parse($this->filters['period'][0])->diffInDays(Carbon::parse($this->filters['period'][1])) + 1;

        return $this->getTotalExpenseAfterTax / $days;
    }

    public function getAverageExpenseValue()
    {
        if ($this->getExpenseTransactionCount == 0) {
            return $this->getExpenseTransactionCount;
        }

        return $this->getTotalExpenseAfterTax / $this->getExpenseTransactionCount;
    }

    public function getPaymentTypesByExpense()
    {
        return (clone $this->query)
            ->selectRaw('
                SUM(quantity*unit_price*(1+taxes.amount)) AS expense,
                expenses.payment_type AS payment_type,
                COUNT(payment_type) AS transactions
            ')
            ->groupBy('payment_type')
            ->orderByDesc('expense')
            ->get();
    }

    public function getTaxTypesByExpense()
    {
        return (clone $this->query)
            ->selectRaw('
                SUM(quantity*unit_price*(1+taxes.amount)) AS expense,
                taxes.type AS tax_type,
                COUNT(taxes.type) AS transactions
            ')
            ->groupBy('taxes.type')
            ->orderByDesc('expense')
            ->get();
    }

    public function getExpensesByBanks()
    {
        return (clone $this->query)
            ->selectRaw('
                SUM(quantity*unit_price*(1+taxes.amount)) AS expense,
                expenses.bank_name AS bank_name
            ')
            ->groupBy('bank_name')
            ->orderByDesc('expense')
            ->get();
    }
}
