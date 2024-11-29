<?php

namespace App\DataTables\Admin;

use App\Models\Company;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CompanyDatatable extends DataTable
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
            ->editColumn('type', fn($school) => str()->ucfirst($school->schoolType->name))
            ->editColumn('status', fn($school) => view('components.datatables.company-status', compact('school')))
            ->editColumn('plan', fn($school) => str()->ucfirst($school->plan->name))
            ->editColumn('is_in_training', fn($school) => $school->isInTraining() ? 'Training Mode' : 'Live Mode')
            ->editColumn('created_at', fn($school) => $school->created_at->toFormattedDateString())
            ->editColumn('actions', fn($school) => view('components.datatables.company-action', compact('school')))
            ->addIndexColumn();
    }

    public function query(Company $school)
    {
        return $school
            ->newQuery()
            ->select('companies.*')
            ->when(request('status') == 'active', fn($q) => $q->enabled())
            ->when(request('status') == 'deactivated', fn($q) => $q->disabled())
            ->with([
                'plan',
                'schoolType:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('name'),
            Column::make('company_code')->content('N/A')->title('School Code'),
            Column::make('type', 'schoolType.name')->content('N/A'),
            Column::computed('status'),
            Column::make('plan', 'plan.name')->content('N/A'),
            Column::make('is_in_training')->title('Mode'),
            Column::make('created_at')->addClass('has-text-right'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Companies_' . date('YmdHis');
    }
}
