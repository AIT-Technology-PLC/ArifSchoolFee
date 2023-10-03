<?php

namespace App\DataTables;

use App\Models\Job;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class JobDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($job) => route('jobs.show', $job->id),
                'x-data' => 'showRowDetails',
                'x-on:click' => 'showDetails',
            ])
            ->editColumn('branch', fn($job) => $job->warehouse->name)
            ->editColumn('prepared by', fn($job) => $job->createdBy->name)
            ->editColumn('issued_on', fn($job) => $job->issued_on->toFormattedDateString())
            ->editColumn('due_date', fn($job) => $job->due_date->toFormattedDateString())
            ->editColumn('status', fn($job) => view('components.datatables.job-status', compact('job')))
            ->editColumn('schedule', fn($job) => view('components.datatables.job-schedule', compact('job')))
            ->editColumn('factory', fn($job) => $job->factory->name)
            ->editColumn('customer', fn($job) => $job->customer->company_name ?? 'N/A')
            ->editColumn('approved by', fn($job) => $job->approvedBy->name ?? 'N/A')
            ->editColumn('closed by', fn($job) => $job->closedBy->name ?? 'N/A')
            ->editColumn('description', fn($job) => $job->description)
            ->editColumn('edited by', fn($job) => $job->updatedBy->name)
            ->editColumn('actions', function ($job) {
                return view('components.common.action-buttons', [
                    'model' => 'jobs',
                    'id' => $job->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Job $job)
    {
        return $job
            ->newQuery()
            ->when(request('progress') == 'completed', fn($q) => $q->whereHas('jobDetails', fn($query) => $query->whereColumn('available', 'quantity')))
            ->when(request('progress') == 'in process', fn($q) => $q->whereHas('jobDetails', fn($query) => $query->whereRaw('job_details.AVAILABLE + job_details.WIP > 0 AND job_details.AVAILABLE <> job_details.QUANTITY')))
            ->when(request('progress') == 'not started', fn($q) => $q->whereHas('jobDetails', fn($query) => $query->whereRaw('job_details.AVAILABLE + job_details.WIP = 0')))
            ->when(request('status') == 'approved', fn($query) => $query->Approved())
            ->when(request('status') == 'waiting approval', fn($query) => $query->notApproved())
            ->when(request('type') == 'inventory replenishment', fn($query) => $query->internal())
            ->when(request('type') == 'customer order', fn($query) => $query->notInternal())
            ->select('job_orders.*')
            ->with([
                'jobDetails',
                'warehouse:id,name',
                'createdBy:id,name',
                'updatedBy:id,name',
                'approvedBy:id,name',
                'closedBy:id,name',
                'factory:id,name',
                'customer:id,company_name',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('branch', 'warehouse.name')->visible(false),
            Column::make('code')->className('has-text-centered')->title('Jobs No'),
            Column::computed('status')->orderable(false),
            Column::computed('schedule')->orderable(false)->searchable(false),
            Column::make('factory', 'factory.name'),
            Column::make('customer', 'customer.company_name')->visible(false),
            Column::make('issued_on'),
            Column::make('due_date')->visible(false),
            Column::make('description')->visible(false),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::make('closed by', 'closedBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];

        return Arr::where($columns, fn($column) => $column != null);
    }

    protected function filename(): string
    {
        return 'Job_' . date('YmdHis');
    }
}
