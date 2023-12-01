<?php

namespace App\DataTables\Admin;

use App\Models\User;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('permissions', fn($user) => $user->permissions->pluck('name')->join(', '))
            ->editColumn('created_at', fn($user) => $user->created_at->toFormattedDateString())
            ->editColumn('last_online_at', fn($user) => $user->last_online_at?->toDayDateTimeString() ?? 'New User')
            ->editColumn('actions', function ($user) {
                return view('components.common.action-buttons', [
                    'model' => 'admin.users',
                    'id' => $user->id,
                    'buttons' => ['edit'],
                ]);
            })->addIndexColumn();
    }

    public function query(User $user)
    {
        return $user
            ->newQuery()
            ->where('is_admin', 1)
            ->select('users.*')
            ->with([
                'permissions',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('name')->addClass('has-text-weight-bold'),
            Column::make('email'),
            Column::make('permissions', 'permissions.name')->visible(false),
            Column::make('created_at')->addClass('has-text-right')->title('Registration Date'),
            Column::make('last_online_at')->addClass('has-text-right')->title('Last Login'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Users_' . date('YmdHis');
    }
}
