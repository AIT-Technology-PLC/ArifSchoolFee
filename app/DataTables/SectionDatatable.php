<?php

namespace App\DataTables;

use App\Models\Section;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SectionDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('created at', fn($section) => $section->created_at->toFormattedDateString())
            ->editColumn('created by', fn($section) => $section->createdBy->name)
            ->editColumn('edited by', fn($section) => $section->updatedBy->name)
            ->editColumn('actions', function ($section) {
                return view('components.common.action-buttons', [
                    'model' => 'sections',
                    'id' => $section->id,
                    'buttons' => ['edit','delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Section $section)
    {
        return $section
            ->newQuery()
            ->select('sections.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('name'),
            Column::make('created by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::make('created at', 'created_at'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Sections_' . date('YmdHis');
    }
}
