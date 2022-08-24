<?php

namespace App\DataTables;

use App\Models\Expense;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ExpenseDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($expense) => route('expenses.show', $expense->id),
                'x-data' => 'showRowDetails',
                '@click' => 'showDetails',
            ])
            ->editColumn('status', fn($expense) => view('components.datatables.expense-status', compact('expense')))
            ->editColumn('total price', function ($expense) {
                if (userCompany()->isDiscountBeforeVAT()) {
                    return money($expense->grandTotalPrice);
                }

                return money($expense->grandTotalPriceAfterDiscount);
            })
            ->editColumn('supplier', fn($expense) => $expense->supplier->company_name ?? 'N/A')
            ->editColumn('tax_type', fn($expense) => $expense->tax_type)
            ->editColumn('issued_on', fn($expense) => $expense->issued_on->toFormattedDateString())
            ->editColumn('prepared by', fn($expense) => $expense->createdBy->name)
            ->editColumn('edited by', fn($expense) => $expense->updatedBy->name)
            ->editColumn('approved by', fn($expense) => $expense->approvedBy->name ?? 'N/A')
            ->editColumn('actions', function ($expense) {
                return view('components.common.action-buttons', [
                    'model' => 'expenses',
                    'id' => $expense->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Expense $expense)
    {
        return $expense
            ->newQuery()
            ->when(request('status') == 'approved', fn($query) => $query->approved())
            ->when(request('status') == 'waiting approval', fn($query) => $query->notApproved())
            ->select('expenses.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'approvedBy:id,name',
                'supplier:id,company_name',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('code')->className('has-text-centered')->title('Reference No'),
            Column::computed('status'),
            Column::computed('total price'),
            Column::make('supplier', 'supplier.company_name'),
            Column::make('tax_type'),
            Column::make('prepared by', 'createdBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];

        return Arr::where($columns, fn($column) => $column != null);
    }

    protected function filename()
    {
        return 'Expense_' . date('YmdHis');
    }
}