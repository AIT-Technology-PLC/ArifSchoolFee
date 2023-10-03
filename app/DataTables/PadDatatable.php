<?php

namespace App\DataTables;

use App\Models\Pad;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PadDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($pad) => route('pads.show', $pad->id),
                'x-data' => 'showRowDetails',
                '@click' => 'showDetails',
            ])
            ->editColumn('status', fn($pad) => view('components.datatables.pad-status', compact('pad')))
            ->filterColumn('status', function ($query, $keyword) {
                $query
                    ->when($keyword == 'enabled', fn($query) => $query->enabled())
                    ->when($keyword == 'disabled', fn($query) => $query->disabled());
            })
            ->editColumn('created on', fn($pad) => $pad->created_at->toFormattedDateString())
            ->editColumn('created by', fn($pad) => $pad->createdBy->name)
            ->editColumn('actions', function ($pad) {
                return view('components.common.action-buttons', [
                    'model' => 'pads',
                    'id' => $pad->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Pad $pad)
    {
        return $pad
            ->newQuery()
            ->when(request('status') == 'enabled', fn($query) => $query->enabled())
            ->when(request('status') == 'disabled', fn($query) => $query->disabled())
            ->select('pads.*')
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
            Column::make('status')->orderable(false),
            Column::make('module'),
            Column::make('inventory_operation_type')->addClass('is-capitalized'),
            Column::make('created on', 'created_at')->className('has-text-right'),
            Column::make('created by', 'createdBy.name'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Pads_' . date('YmdHis');
    }
}
