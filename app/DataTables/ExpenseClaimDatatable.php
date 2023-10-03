<?php

namespace App\DataTables;

use App\Models\ExpenseClaim;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ExpenseClaimDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($expenseClaim) => route('expense-claims.show', $expenseClaim->id),
                'x-data' => 'showRowDetails',
                '@click' => 'showDetails',
            ])
            ->editColumn('branch', fn($expenseClaim) => $expenseClaim->warehouse->name)
            ->editColumn('status', fn($expenseClaim) => view('components.datatables.expense-claim-status', compact('expenseClaim')))
            ->editColumn('employee', fn($expenseClaim) => $expenseClaim->employee->user->name)
            ->editColumn('issued_on', fn($expenseClaim) => $expenseClaim->issued_on->toFormattedDateString())
            ->editColumn('prepared by', fn($expenseClaim) => $expenseClaim->createdBy->name)
            ->editColumn('approved by', fn($expenseClaim) => $expenseClaim->approvedBy->name ?? 'N/A')
            ->editColumn('rejected by', fn($expenseClaim) => $expenseClaim->rejectedBy->name ?? 'N/A')
            ->editColumn('edited by', fn($expenseClaim) => $expenseClaim->updatedBy->name)
            ->editColumn('actions', function ($expenseClaim) {
                return view('components.common.action-buttons', [
                    'model' => 'expense-claims',
                    'id' => $expenseClaim->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(ExpenseClaim $expenseClaim)
    {
        return $expenseClaim
            ->newQuery()
            ->when(request('status') == 'approved', fn($query) => $query->approved()->notRejected())
            ->when(request('status') == 'waiting approval', fn($query) => $query->notApproved()->notRejected())
            ->when(request('status') == 'rejected', fn($query) => $query->rejected())
            ->select('expense_claims.*')
            ->with([
                'employee.user',
                'createdBy:id,name',
                'updatedBy:id,name',
                'approvedBy:id,name',
                'rejectedBy:id,name',
                'warehouse:id,name',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('branch', 'warehouse.name')->visible(false),
            Column::make('code')->className('has-text-centered')->title('Claim No'),
            Column::computed('status'),
            Column::make('employee', 'employee.user.name'),
            Column::make('issued_on'),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::make('rejected by', 'rejectedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];

        return Arr::where($columns, fn($column) => $column != null);
    }

    protected function filename(): string
    {
        return 'ExpenseClaim_' . date('YmdHis');
    }
}
