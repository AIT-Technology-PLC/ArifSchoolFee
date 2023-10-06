<?php

namespace App\DataTables;

use App\Models\ExpenseCategory;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ExpenseCategoryDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('created by', fn($expenseCategory) => $expenseCategory->createdBy->name)
            ->editColumn('edited by', fn($expenseCategory) => $expenseCategory->updatedBy->name)
            ->editColumn('actions', function ($expenseCategory) {
                return view('components.common.action-buttons', [
                    'model' => 'expense-categories',
                    'id' => $expenseCategory->id,
                    'buttons' => ['edit', 'delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(ExpenseCategory $expenseCategory)
    {
        return $expenseCategory
            ->newQuery()
            ->select('expense_categories.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('name')->addClass('text-green has-text-weight-bold'),
            Column::make('created by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'ExpenseCategory_' . date('YmdHis');
    }
}
