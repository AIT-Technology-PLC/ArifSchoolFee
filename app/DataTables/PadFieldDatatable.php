<?php

namespace App\DataTables;

use App\Models\PadField;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PadFieldDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('relationship_type', fn($padField) => $padField->padRelation->relationship_type ?? '-')
            ->editColumn('model_name', fn($padField) => $padField->padRelation->model_name ?? '-')
            ->editColumn('representative_column', fn($padField) => $padField->padRelation->representative_column ?? '-')
            ->editColumn('component_name', fn($padField) => $padField->padRelation->component_name ?? '-')
            ->editColumn('is_master_field', fn($padField) => $padField->is_master_field ? 'Yes' : 'No')
            ->editColumn('is_required', fn($padField) => $padField->is_required ? 'Yes' : 'No')
            ->editColumn('is_visible', fn($padField) => $padField->is_visible ? 'Yes' : 'No')
            ->editColumn('is_printable', fn($padField) => $padField->is_printable ? 'Yes' : 'No')
            ->editColumn('is_readonly', fn($padField) => $padField->is_readonly ? 'Yes' : 'No')
            ->editColumn('tag_type', fn($padField) => $padField->tag_type ?? '-')
            ->editColumn('actions', function ($padField) {
                return view('components.common.action-buttons', [
                    'model' => 'pad-fields',
                    'id' => $padField->id,
                    'buttons' => ['delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(PadField $padField)
    {
        return $padField
            ->newQuery()
            ->select('pad_fields.*')
            ->where('pad_id', request()->route('pad')->id)
            ->with(['padRelation']);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('label')->addClass('text-green has-text-weight-bold'),
            Column::make('icon'),
            Column::make('relationship_type', 'padRelation.relationship_type'),
            Column::make('model_name', 'padRelation.model_name'),
            Column::make('representative_column', 'padRelation.representative_column'),
            Column::make('component_name', 'padRelation.component_name'),
            Column::make('is_master_field')->title('Master Field'),
            Column::make('is_required')->title('Required'),
            Column::make('is_visible')->title('Visible'),
            Column::make('is_printable')->title('Printable'),
            Column::make('is_readonly')->title('Readonly'),
            Column::make('tag'),
            Column::make('tag_type'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Pad Fields_' . date('YmdHis');
    }
}
