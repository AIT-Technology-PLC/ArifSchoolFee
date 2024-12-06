<?php

namespace App\DataTables\Admin;

use App\Models\Company;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PendingCompanyDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($school) => route('admin.schools.show', $school->id),
                'x-data' => 'showRowDetails',
                'x-on:click' => 'showDetails',
            ])
            ->editColumn('name', fn($school) => str()->ucfirst($school->name))
            ->editColumn('status', fn($school) => view('components.datatables.company-status', compact('school')))
            ->editColumn('plan', fn($school) => str()->ucfirst($school->plan->name))
            ->editColumn('type', fn($school) => str()->ucfirst($school->schoolType->name))
            ->editColumn('created_at', fn($school) => $school->created_at->toFormattedDateString())
            ->editColumn('actions', function ($school) {
                return view('components.common.action-buttons', [
                    'model' => 'admin.schools',
                    'id' => $school->id,
                    'buttons' => ['details'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Company $school)
    {
        return $school
            ->newQuery()
            ->select('companies.*')
            ->disabled()
            ->with([
                'plan',
                'schoolType:id,name',
            ])
            ->orderBy('created_at', 'DESC');
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('name'),
            Column::make('type', 'schoolType.name')->content('N/A'),
            Column::computed('status'),
            Column::make('plan', 'plan.name')->content('N/A'),
            Column::make('created_at'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Pending Companies' . date('YmdHis');
    }
}

