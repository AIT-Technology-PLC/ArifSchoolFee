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
                'data-url' => fn($company) => route('admin.companies.show', $company->id),
                'x-data' => 'showRowDetails',
                'x-on:click' => 'showDetails',
            ])
            ->editColumn('name', fn($company) => str()->ucfirst($company->name))
            ->editColumn('status', fn($company) => view('components.datatables.company-status', compact('company')))
            ->editColumn('plan', fn($company) => str()->ucfirst($company->plan->name))
            ->editColumn('type', fn($company) => str()->ucfirst($company->schoolType->name))
            ->editColumn('created_at', fn($company) => $company->created_at->toFormattedDateString())
            ->editColumn('actions', function ($company) {
                return view('components.common.action-buttons', [
                    'model' => 'admin.schools',
                    'id' => $company->id,
                    'buttons' => ['details'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Company $company)
    {
        return $company
            ->newQuery()
            ->select('companies.*')
            ->disabled()
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

