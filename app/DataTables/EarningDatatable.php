<?php

namespace App\DataTables;

use App\Models\Earning;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EarningDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($earning) => route('earnings.show', $earning->id),
                'x-data' => 'showRowDetails',
                '@click' => 'showDetails',
            ])
            ->editColumn('branch', fn($earning) => $earning->warehouse->name)
            ->editColumn('starting_period', fn($earning) => $earning->starting_period->toFormattedDateString())
            ->editColumn('ending_period', fn($earning) => $earning->ending_period->toFormattedDateString())
            ->editColumn('status', fn($earning) => view('components.datatables.earning-status', compact('earning')))
            ->editColumn('prepared by', fn($earning) => $earning->createdBy->name)
            ->editColumn('edited by', fn($earning) => $earning->updatedBy->name)
            ->editColumn('approved by', fn($earning) => $earning->approvedBy->name ?? 'N/A')
            ->editColumn('description', fn($earning) => $earning->description ?? 'N/A')
            ->editColumn('actions', function ($earning) {
                return view('components.common.action-buttons', [
                    'model' => 'earnings',
                    'id' => $earning->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Earning $earning)
    {
        return $earning
            ->newQuery()
            ->when(request('status') == 'approved', fn($query) => $query->approved())
            ->when(request('status') == 'waiting approval', fn($query) => $query->notApproved())
            ->select('earnings.*')
            ->with([
                'earningDetails',
                'warehouse:id,name',
                'createdBy:id,name',
                'updatedBy:id,name',
                'approvedBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('branch', 'warehouse.name')->visible(false),
            Column::make('code')->className('has-text-centered')->title('Earnings No'),
            Column::computed('status')->orderable(false),
            Column::make('starting_period'),
            Column::make('ending_period'),
            Column::make('description')->visible(false),
            Column::make('prepared by', 'createdBy.name')->visible(false),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];

        return Arr::where($columns, fn($column) => $column != null);
    }

    protected function filename()
    {
        return 'Earning_' . date('YmdHis');
    }
}
