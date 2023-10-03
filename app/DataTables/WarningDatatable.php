<?php

namespace App\DataTables;

use App\Models\Warning;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class WarningDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($warning) => route('warnings.show', $warning->id),
                'x-data' => 'showRowDetails',
                'x-on:click' => 'showDetails',
            ])
            ->editColumn('status', fn($warning) => view('components.datatables.warning-status', compact('warning')))
            ->editColumn('type', fn($warning) => view('components.datatables.warning-type', compact('warning')))
            ->editColumn('employee name', fn($warning) => $warning->employee->user->name)
            ->editColumn('branch', fn($warning) => $warning->warehouse->name)
            ->editColumn('issued_on', fn($warning) => $warning->issued_on->toFormattedDateString())
            ->editColumn('created by', fn($warning) => $warning->createdBy->name)
            ->editColumn('approved by', fn($warning) => $warning->approvedBy->name ?? 'N/A')
            ->editColumn('actions', function ($warning) {
                return view('components.common.action-buttons', [
                    'model' => 'warnings',
                    'id' => $warning->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Warning $warning)
    {
        return $warning
            ->newQuery()
            ->when(request('status') == 'approved', fn($query) => $query->approved())
            ->when(request('status') == 'waiting approval', fn($query) => $query->notApproved())
            ->when(request('type') == 'initial warning', fn($query) => $query->initial())
            ->when(request('type') == 'affirmation warning', fn($query) => $query->affirmation())
            ->when(request('type') == 'final warning', fn($query) => $query->final())
            ->select('warnings.*')
            ->with([
                'createdBy:id,name',
                'approvedBy:id,name',
                'warehouse:id,name',
                'employee.user',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('code')->className('has-text-centered')->title('Warning No'),
            Column::make('employee name', 'employee.user.name'),
            Column::make('branch', 'warehouse.name')->visible(false),
            Column::computed('status')->orderable(false),
            Column::computed('type')->orderable(false),
            Column::make('issued_on'),
            Column::make('created by', 'createdBy.name')->visible(false),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Warning_' . date('YmdHis');
    }
}
