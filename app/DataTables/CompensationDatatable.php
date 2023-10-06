<?php

namespace App\DataTables;

use App\Models\Compensation;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CompensationDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('name', fn($compensation) => $compensation->name)
            ->editColumn('type', fn($compensation) => $compensation->type)
            ->editColumn('is_active', fn($compensation) => $compensation->isActive() ? 'Yes' : 'No')
            ->editColumn('is_taxable', fn($compensation) => $compensation->isTaxable() ? 'Yes' : 'No')
            ->editColumn('is_adjustable', fn($compensation) => $compensation->isAdjustable() ? 'Yes' : 'No')
            ->editColumn('can_be_inputted_manually', fn($compensation) => $compensation->canBeInputtedManually() ? 'Yes' : 'No')
            ->editColumn('has_formula', fn($compensation) => $compensation->hasFormula() ? 'Yes' : 'No')
            ->editColumn('percentage', fn($compensation) => $compensation->percentage ?? 'N/A')
            ->editColumn('default_value', fn($compensation) => $compensation->default_value ?? 'N/A')
            ->editColumn('maximum_amount', fn($compensation) => $compensation->maximum_amount ?? 'N/A')
            ->editColumn('created by', fn($compensation) => $compensation->createdBy->name)
            ->editColumn('edited by', fn($compensation) => $compensation->updatedBy->name)
            ->editColumn('actions', function ($compensation) {
                return view('components.common.action-buttons', [
                    'model' => 'compensations',
                    'id' => $compensation->id,
                    'buttons' => ['edit'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Compensation $compensation)
    {
        return $compensation
            ->newQuery()
            ->select('compensations.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('name'),
            Column::make('type'),
            Column::make('is_active'),
            Column::make('is_taxable'),
            Column::make('is_adjustable'),
            Column::make('can_be_inputted_manually'),
            Column::make('has_formula'),
            Column::make('percentage'),
            Column::make('default_value')->visible(false),
            Column::make('maximum_amount')->visible(false),
            Column::make('created by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];

        return Arr::where($columns, fn($column) => $column != null);
    }

    protected function filename(): string
    {
        return 'Compensation_' . date('YmdHis');
    }
}
