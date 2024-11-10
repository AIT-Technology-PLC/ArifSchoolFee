<?php

namespace App\DataTables;

use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Column;
use Spatie\Activitylog\Models\Activity;

class ActivityLogDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('created_at', fn($activityLog) => $activityLog->created_at->format('Y-m-d  H:i:s'))
            ->editColumn('name', fn($activityLog) => $activityLog->createdBy?->name)
            ->editColumn('email', fn($activityLog) => $activityLog->createdBy?->email)
            ->editColumn('event', fn ($activityLog) => view('components.datatables.activity-log-status', compact('activityLog')))
            ->addIndexColumn();
    }

    public function query(Activity $activityLog)
    {
        return $activityLog
            ->newQuery()
            ->select('activity_log.*')
            ->with([
                'createdBy:id,name,email',
            ])
            ->orderBy('created_at', 'DESC');;
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('name', 'createdBy.name')->title('Full Name')->content('N/A'),
            Column::make('email', 'createdBy.email')->content('N/A'),
            Column::make('log_name')->title('Log Name'),
            Column::make('ip_address')->title('IP Address'),
            Column::make('created_at')->title('Date-Time'),
            Column::make('event')->title('Action'),
        ];
    }

    protected function filename(): string
    {
        return 'Activity Logs' . date('YmdHis');
    }
}
