<?php

namespace App\DataTables;

use App\Models\Payroll;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PayrollDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($payroll) => route('payrolls.show', $payroll->id),
                'x-data' => 'showRowDetails',
                'x-on:click' => 'showDetails',
            ])
            ->editColumn('status', fn($payroll) => view('components.datatables.payroll-status', compact('payroll')))
            ->editColumn('bank_name', fn($payroll) => $payroll->company->payroll_bank_name ?? 'N/A')
            ->editColumn('issued_on', fn($payroll) => $payroll->issued_on->toFormattedDateString())
            ->editColumn('paid_at', fn($payroll) => $payroll->paid_at?->toFormattedDateString() ?? 'Not Paid')
            ->editColumn('starting_period', fn($payroll) => $payroll->starting_period->toDateString())
            ->editColumn('ending_period', fn($payroll) => $payroll->ending_period->toDateString())
            ->editColumn('prepared by', fn($payroll) => $payroll->createdBy->name)
            ->editColumn('approved by', fn($payroll) => $payroll->approvedBy->name ?? 'N/A')
            ->editColumn('paid by', fn($payroll) => $payroll->paidBy->name ?? 'N/A')
            ->editColumn('edited by', fn($payroll) => $payroll->updatedBy->name)
            ->editColumn('actions', function ($payroll) {
                return view('components.common.action-buttons', [
                    'model' => 'payrolls',
                    'id' => $payroll->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Payroll $payroll)
    {
        return $payroll
            ->newQuery()
            ->when(request('status') == 'approved', fn($query) => $query->approved()->notPaid())
            ->when(request('status') == 'waiting approval', fn($query) => $query->notApproved()->notPaid())
            ->when(request('status') == 'paid', fn($query) => $query->paid())
            ->select('payrolls.*')
            ->with([
                'company',
                'createdBy:id,name',
                'updatedBy:id,name',
                'approvedBy:id,name',
                'paidBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('code')->className('has-text-centered')->title('Reference No'),
            Column::computed('status'),
            Column::make('bank_name', 'company.payroll_bank_name'),
            Column::make('issued_on'),
            Column::make('paid_at')->title('Paid On'),
            Column::make('starting_period')->visible(false),
            Column::make('ending_period')->visible(false),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::make('paid by', 'paidBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];

        return Arr::where($columns, fn($column) => $column != null);
    }

    protected function filename(): string
    {
        return 'Payroll_' . date('YmdHis');
    }
}
