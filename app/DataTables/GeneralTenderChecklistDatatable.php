<?php

namespace App\DataTables;

use App\Models\GeneralTenderChecklist;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class GeneralTenderChecklistDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('type', fn($generalTenderChecklist) => $generalTenderChecklist->tenderChecklistType->name)
            ->editColumn('description', fn($generalTenderChecklist) => view('components.datatables.searchable-description', ['description' => $generalTenderChecklist->description]))
            ->editColumn('added on', fn($generalTenderChecklist) => $generalTenderChecklist->created_at->toDayDateTimeString())
            ->editColumn('added by', fn($generalTenderChecklist) => $generalTenderChecklist->createdBy->name)
            ->editColumn('edited by', fn($generalTenderChecklist) => $generalTenderChecklist->updatedBy->name)
            ->editColumn('actions', function ($generalTenderChecklist) {
                return view('components.common.action-buttons', [
                    'model' => 'general-tender-checklists',
                    'id' => $generalTenderChecklist->id,
                    'buttons' => ['edit', 'delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(GeneralTenderChecklist $generalTenderChecklist)
    {
        return $generalTenderChecklist
            ->newQuery()
            ->select('general_tender_checklists.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'tenderChecklistType',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('item')->addClass('text-green has-text-weight-bold'),
            Column::make('type', 'tenderChecklistType.name'),
            Column::make('description'),
            Column::make('added on', 'created_at')->className('has-text-right'),
            Column::make('added by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'GeneralTenderChecklists_' . date('YmdHis');
    }
}
