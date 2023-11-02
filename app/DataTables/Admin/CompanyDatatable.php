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
                'data-url' => fn($company) => route('admin.companies.show', $company->id),
                'x-data' => 'showRowDetails',
                'x-on:click' => 'showDetails',
            ])
            ->editColumn('name', fn($company) => $company->name)
            ->editColumn('status', fn($company) => view('components.datatables.company-status', compact('company')))
            ->editColumn('plan', fn($company) => $company->plan->name)
            ->editColumn('created_at', fn($company) => $company->created_at->toFormattedDateString())
            ->editColumn('actions', function ($company) {
                return view('components.common.action-buttons', [
                    'model' => 'admin.companies',
                    'id' => $company->id,
                    'buttons' => ['details'],
                ]);
            })->addIndexColumn();
    }

    public function query(Company $company)
    {
        return $company
            ->newQuery()
            ->select('companies.*')
            ->when(request('status') == 'active', fn($q) => $q->enabled())
            ->when(request('status') == 'deactivated', fn($q) => $q->disabled())
            ->with([
                'plan',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('name')->addClass('has-text-weight-bold'),
            Column::computed('status'),
            Column::make('plan', 'plan.name'),
            Column::make('created_at')->addClass('has-text-right'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Companies_' . date('YmdHis');
    }
}
