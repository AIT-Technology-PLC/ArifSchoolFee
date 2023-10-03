<?php

namespace App\DataTables;

use App\Models\CostUpdate;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CostUpdateDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($costUpdate) => route('cost-updates.show', $costUpdate->id),
                'x-data' => 'showRowDetails',
                '@click' => 'showDetails',
            ])
            ->editColumn('Reference No', fn($costUpdate) => $costUpdate->costUpdate->code ?? 'N/A')
            ->editColumn('status', fn($costUpdate) => view('components.datatables.cost-update-status', compact('costUpdate')))
            ->filterColumn('status', function ($query, $keyword) {
                $query
                    ->when($keyword == 'waiting-approval', fn($query) => $query->notApproved()->notRejected())
                    ->when($keyword == 'approved', fn($query) => $query->approved())
                    ->when($keyword == 'rejected', fn($query) => $query->rejected());
            })
            ->editColumn('created at', fn($costUpdate) => $costUpdate->created_at->toFormattedDateString())
            ->editColumn('prepared by', fn($costUpdate) => $costUpdate->createdBy->name)
            ->editColumn('approved by', fn($costUpdate) => $costUpdate->approvedBy->name ?? 'N/A')
            ->editColumn('rejected by', fn($costUpdate) => $costUpdate->rejectedBy->name ?? 'N/A')
            ->editColumn('edited by', fn($costUpdate) => $costUpdate->updatedBy->name)
            ->editColumn('actions', function ($costUpdate) {
                return view('components.common.action-buttons', [
                    'model' => 'cost-updates',
                    'id' => $costUpdate->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(CostUpdate $costUpdate)
    {
        return $costUpdate
            ->newQuery()
            ->select('cost_updates.*')
            ->when(request('status') == 'waiting approval', fn($query) => $query->notApproved()->notRejected())
            ->when(request('status') == 'approved', fn($query) => $query->approved())
            ->when(request('status') == 'rejected', fn($query) => $query->rejected())
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'approvedBy:id,name',
                'rejectedBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('code')->className('has-text-centered')->title('Reference No'),
            Column::make('status')->orderable(false),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('created at'),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::make('rejected by', 'rejectedBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];

        return Arr::where($columns, fn($column) => $column != null);
    }

    protected function filename(): string
    {
        return 'CostUpdate_' . date('YmdHis');
    }
}
