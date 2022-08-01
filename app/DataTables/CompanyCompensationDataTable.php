<?php

namespace App\DataTables;

use App\Models\CompanyCompensation;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CompanyCompensationDataTable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('name', fn($companyCompensation) => $companyCompensation->name)
            ->editColumn('type', fn($companyCompensation) => $companyCompensation->type)
            ->editColumn('is_taxable', fn($companyCompensation) => $companyCompensation->is_taxable)
            ->editColumn('is_adjustable', fn($companyCompensation) => $companyCompensation->is_adjustable)
            ->editColumn('percentage', fn($companyCompensation) => $companyCompensation->percentage)
            ->editColumn('created by', fn($companyCompensation) => $companyCompensation->createdBy->name)
            ->editColumn('edited by', fn($companyCompensation) => $companyCompensation->updatedBy->name)
            ->editColumn('actions', function ($companyCompensation) {
                return view('components.common.action-buttons', [
                    'model' => 'company_compensations',
                    'id' => $companyCompensation->id,
                    'buttons' => ['edit', 'delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(CompanyCompensation $companyCompensation)
    {
        return $companyCompensation
            ->newQuery()
            ->select('company_compensations.*')
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
            Column::computed('status'),
            Column::make('type'),
            Column::make('is_taxable'),
            Column::make('is_adjustable'),
            Column::make('percentage'),
            Column::make('created by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];

        return Arr::where($columns, fn($column) => $column != null);
    }
    protected function filename()
    {
        return 'CompanyCompensation_' . date('YmdHis');
    }
}