<?php

namespace App\DataTables;

use App\Models\TenderStatus;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TenderStatusDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('description', fn($tenderStatus) => view('components.datatables.searchable-description', ['description' => $tenderStatus->description]))
            ->editColumn('added by', fn($tenderStatus) => $tenderStatus->createdBy->name)
            ->editColumn('edited by', fn($tenderStatus) => $tenderStatus->updatedBy->name)
            ->editColumn('actions', function ($tenderStatus) {
                return view('components.common.action-buttons', [
                    'model' => 'tender-statuses',
                    'id' => $tenderStatus->id,
                    'buttons' => ['edit', 'delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(TenderStatus $tenderStatus)
    {
        return $tenderStatus
            ->newQuery()
            ->select('tender_statuses.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('status')->addClass('text-green has-text-weight-bold'),
            Column::make('description')->visible(false),
            Column::make('added by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'TenderStatuses_' . date('YmdHis');
    }
}
