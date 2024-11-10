<?php

namespace App\DataTables;

use App\Models\Warehouse;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BranchDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('status', fn($branch) => view('components.datatables.branch-status', compact('branch')))
            ->filterColumn('status', function ($query, $keyword) {
                $query
                    ->when($keyword == 'active', fn($query) => $query->active())
                    ->when($keyword == 'inactive', fn($query) => $query->inactive());
            })
            ->editColumn('created on', fn($branch) => $branch->created_at->toFormattedDateString())
            ->editColumn('created by', fn($branch) => $branch->createdBy->name)
            ->editColumn('edited by', fn($branch) => $branch->updatedBy->name)
            ->editColumn('actions', function ($branch) {
                return view('components.common.action-buttons', [
                    'model' => 'branches',
                    'id' => $branch->id,
                    'buttons' => ['edit'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Warehouse $branch)
    {
        return $branch
            ->newQuery()
            ->when(request('status') == 'active', fn($query) => $query->active())
            ->when(request('status') == 'inactive', fn($query) => $query->inactive())
            ->select('warehouses.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        return collect([
            Column::computed('#'),
            Column::make('name')->addClass('has-text-weight-bold'),
            Column::make('location'),
            Column::make('status')->orderable(false),
            Column::make('email')->content('N/A'),
            Column::make('phone')->content('N/A')->visible(false),
            Column::make('created on', 'created_at')->className('has-text-right'),
            Column::make('created by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ])
            ->filter()
            ->toArray();
    }

    protected function filename(): string
    {
        return 'Branches_' . date('YmdHis');
    }
}
