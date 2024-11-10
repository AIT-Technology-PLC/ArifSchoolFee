<?php

namespace App\DataTables;

use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Column;
use App\Models\UserLog;

class UserLogDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('last_online_at', fn($userLog) => $userLog->last_online_at->format('Y-m-d H:i:s'))
            ->addIndexColumn();
    }

    public function query(UserLog $userLog)
    {
        return $userLog
            ->newQuery()
            ->select('user_logs.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
            ])
            ->orderBy('last_online_at', 'DESC');
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('name')->title('Full Name'),
            Column::make('email'),
            Column::make('phone'),
            Column::make('role'),
            Column::make('ip_address')->title('IP Address'),
            Column::make('last_online_at')->title('Date-Time'),
        ];
    }

    protected function filename(): string
    {
        return 'User Logs' . date('YmdHis');
    }
}
