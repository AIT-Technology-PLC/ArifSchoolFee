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
                '@click' => 'showDetails',
            ])
            ->editColumn('prepared by', fn($job) => $job->createdBy->name)
            ->editColumn('assigned to', fn($job) => $job->assignedTo->name)
            ->editColumn('status', fn($job) => view('components.datatables.job-status', compact('job')))
            ->editColumn('factory', fn($job) => $job->factory->name)
            ->editColumn('approved by', fn($job) => $job->approvedBy->name ?? 'N/A')
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
            ->when(request('status') == 'done', fn($query) => $query->active())
            ->when(request('status') == 'work in process', fn($query) => $query->active())
            ->select('jobs.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'approvedBy:id,name',
                'assignedTo:id,name',
                'factory:id,name',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('code')->className('has-text-centered')->title('Jobs No'),
            Column::make('prepared by', 'createdBy.name')->visible(false),
            Column::make('assigned to', 'assignedTo.name'),
            Column::make('status')->orderable(false),
            Column::make('factory', 'factory.name'),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::make('description'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];

        return Arr::where($columns, fn($column) => $column != null);
    }

    protected function filename()
    {
        return 'Job_' . date('YmdHis');
    }
}