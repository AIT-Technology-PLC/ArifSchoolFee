<?php

namespace App\DataTables;

use App\Models\ExpenseClaimDetail;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ExpenseClaimDetailDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('item', fn($expenseClaimDetail) => $expenseClaimDetail->item)
            ->editColumn('price', fn($expenseClaimDetail) => $expenseClaimDetail->price)
            ->editColumn('actions', function ($expenseClaimDetail) {
                return view('components.common.action-buttons', [
                    'model' => 'expense-claim-details',
                    'id' => $expenseClaimDetail->id,
                    'buttons' => ['delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(ExpenseClaimDetail $expenseClaimDetail)
    {
        return $expenseClaimDetail
            ->newQuery()
            ->select('expense_claim_details.*')
            ->where('expense_claim_id', request()->route('expense_claim')->id);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('item'),
            Column::make('price'),
            Column::computed('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'ExpenseClaimDetail_' . date('YmdHis');
    }
}
