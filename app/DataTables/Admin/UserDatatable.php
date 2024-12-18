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
            ->editColumn('user_type', fn($user) => ucfirst($user->user_type))
            ->editColumn('created_at', fn($user) => $user->created_at->toFormattedDateString())
            ->editColumn('last_online_at', fn($user) => $user->last_online_at?->toDayDateTimeString() ?? 'New Admin')
            ->editColumn('status', fn($user) => view('components.datatables.user-status', compact('user')))
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
            ->where(function ($query) {
                $query->where('is_admin', 1) 
                      ->orWhereIn('user_type', ['call_center', 'bank']);
            })
            ->when(request('status') == 'active', fn($query) => $query->allowed())
            ->when(request('status') == 'inactive', fn($query) => $query->notAllowed())
            ->when(request('user_type') == 'admin', fn($query) => $query->where('user_type','admin'))
            ->when(request('user_type') == 'call center', fn($query) => $query->where('user_type','call_center'))
            ->when(request('user_type') == 'bank', fn($query) => $query->where('user_type','bank'))
            ->when(request('bank') == 'bank', fn($query) => $query->where('bank_name','bank'))
            ->when(request('bank') && request('bank') !== 'all', function ($query) {
                $banks = is_array(request('bank')) ? request('bank') : [request('bank')];
                return $query->whereIn('bank_name', $banks); 
            })
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
            Column::make('user_type'),
            Column::make('status'),
            Column::make('bank_name')->content('N/A')->visible(false),
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
