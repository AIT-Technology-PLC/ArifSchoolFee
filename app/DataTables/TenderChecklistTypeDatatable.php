<?php

namespace App\DataTables;

use App\Models\TenderChecklistType;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TenderChecklistTypeDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('confidential', fn($tenderChecklistType) => view('components.datatables.tender-checklist-type-confidentiality', compact('tenderChecklistType')))
            ->editColumn('checklists', fn($tenderChecklistType) => $tenderChecklistType->general_tender_checklists_count)
            ->editColumn('description', fn($tenderChecklistType) => view('components.datatables.searchable-description', ['description' => $tenderChecklistType->description]))
            ->editColumn('added on', fn($tenderChecklistType) => $tenderChecklistType->created_at->toFormattedDateString())
            ->editColumn('added by', fn($tenderChecklistType) => $tenderChecklistType->createdBy->name)
            ->editColumn('edited by', fn($tenderChecklistType) => $tenderChecklistType->updatedBy->name)
            ->editColumn('actions', function ($tenderChecklistType) {
                return view('components.common.action-buttons', [
                    'model' => 'tender-checklist-types',
                    'id' => $tenderChecklistType->id,
                    'buttons' => ['edit', 'delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(TenderChecklistType $tenderChecklistType)
    {
        return $tenderChecklistType
            ->newQuery()
            ->select('tender_checklist_types.*')
            ->withCount('generalTenderChecklists')
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
            Column::make('confidential', 'is_sensitive')->searchable(false),
            Column::computed('checklists'),
            Column::make('description')->visible(false),
            Column::make('added on', 'created_at'),
            Column::make('added by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Tender Checklist Types_' . date('YmdHis');
    }
}
