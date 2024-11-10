<?php

namespace App\DataTables\Admin;

use Spatie\Permission\Models\Role;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RoleDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('created_at', fn($role) => $role->created_at->toFormattedDateString())
            ->editColumn('actions', function ($role) {
                return view('components.common.action-buttons', [
                    'model' => 'admin.roles',
                    'id' => $role->id,
                    'buttons' => ['edit'],
                ]);
            })->addIndexColumn();
    }

    public function query(Role $role)
    {
        return $role
            ->newQuery()
            ->select('roles.*');
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('name'),
            Column::make('created_at'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Roles' . date('YmdHis');
    }
}
