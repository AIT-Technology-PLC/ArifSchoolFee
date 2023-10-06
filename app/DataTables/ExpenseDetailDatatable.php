<?php

namespace App\DataTables;

use App\Models\ExpenseDetail;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ExpenseDetailDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('name', fn($expenseDetail) => $expenseDetail->name)
            ->editColumn('category', fn($expenseDetail) => $expenseDetail->expenseCategory->name)
            ->editColumn('quantity', fn($expenseDetail) => number_format($expenseDetail->quantity, 2))
            ->editColumn('unit_price', fn($expenseDetail) => number_format($expenseDetail->unit_price, 2))
            ->editColumn('actions', function ($expenseDetail) {
                return view('components.common.action-buttons', [
                    'model' => 'expense-details',
                    'id' => $expenseDetail->id,
                    'buttons' => ['delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(ExpenseDetail $expenseDetail)
    {
        return $expenseDetail
            ->newQuery()
            ->select('expense_details.*')
            ->where('expense_id', request()->route('expense')->id)
            ->with([
                'expenseCategory:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('name'),
            Column::make('category', 'expenseCategory.name'),
            Column::make('quantity')->addClass('has-text-right'),
            Column::make('unit_price')->addClass('has-text-right'),
            Column::computed('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'ExpenseDetail_' . date('YmdHis');
    }
}
