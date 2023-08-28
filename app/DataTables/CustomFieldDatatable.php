<?php

namespace App\DataTables;

use App\Models\CustomField;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CustomFieldDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('model_type', fn($customField) => get_class($customField->model_type))
            ->editColumn('is_active', fn($customField) => $customField->is_active ? 'Yes' : 'No')
            ->editColumn('is_required', fn($customField) => $customField->is_required ? 'Yes' : 'No')
            ->editColumn('is_visible', fn($customField) => $customField->is_visible ? 'Yes' : 'No')
            ->editColumn('is_printable', fn($customField) => $customField->is_printable ? 'Yes' : 'No')
            ->editColumn('is_master', fn($customField) => $customField->is_master ? 'Yes' : 'No')
            ->editColumn('created on', fn($customField) => $customField->created_at->toFormattedDateString())
            ->editColumn('created by', fn($customField) => $customField->createdBy->name)
            ->editColumn('edited by', fn($customField) => $customField->updatedBy->name)
            ->editColumn('actions', function ($customField) {
                return view('components.common.action-buttons', [
                    'model' => 'custom-fields',
                    'id' => $customField->id,
                    'buttons' => ['edit', 'delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(CustomField $customField)
    {
        return $customField
            ->newQuery()
            ->select('custom_fields.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('label'),
            Column::make('tag'),
            Column::make('tag_type')->content('N/A'),
            Column::make('placeholder')->content('N/A')->visible(false),
            Column::make('options')->content('N/A')->visible(false),
            Column::make('default_value')->content('N/A')->visible(false),
            Column::make('model_type'),
            Column::make('order')->visible(false),
            Column::make('column_size')->visible(false),
            Column::make('icon')->visible(false),
            Column::make('is_active')->title('Active'),
            Column::make('is_required')->title('Required')->visible(false),
            Column::make('is_visible')->title('Column Visibility')->visible(false),
            Column::make('is_printable')->title('Show on print')->visible(false),
            Column::make('is_master')->title('Master field')->visible(false),
            Column::make('created on', 'created_at')->className('has-text-right'),
            Column::make('created by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename()
    {
        return 'Custom Fields_' . date('YmdHis');
    }
}
