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
            ->editColumn('quantity', fn($expenseDetail) => $expenseDetail->quantity)
            ->editColumn('unit_price', fn($expenseDetail) => $expenseDetail->unit_price)
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
            ->where('expense_id', request()->route('expense')->id);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('name'),
            Column::make('quantity'),
            Column::make('category'),
            Column::make('unit_price'),
            Column::computed('actions'),
        ];
    }

    protected function filename()
    {
        return 'ExpenseDetail_' . date('YmdHis');
    }
}