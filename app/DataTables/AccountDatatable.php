<?php

namespace App\DataTables;

use App\Models\Account;
use App\Models\FeeGroup;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AccountDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('created_at', fn($account) => $account->created_at->toFormattedDateString())
            ->editColumn('status', fn($account) => view('components.datatables.account-status', compact('account')))
            ->editColumn('added by', fn($account) => $account->createdBy->name ?? 'N/A')
            ->editColumn('edited by', fn($account) => $account->updatedBy->name ?? 'N/A')
            ->editColumn('additional_info', fn($account) =>  strip_tags($account->additional_info))
            ->editColumn('actions', function ($account) {
                return view('components.common.action-buttons', [
                    'model' => 'accounts',
                    'id' => $account->id,
                    'buttons' => ['edit'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Account $account)
    {
        return $account
            ->newQuery()
            ->when(request('status') == 'active', fn($query) => $query->active())
            ->when(request('status') == 'inactive', fn($query) => $query->inactive())
            ->select('accounts.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('account_type'),
            Column::make('account_number'),
            Column::make('account_holder'),
            Column::make('status')->orderable(false),
            Column::make('additional_info')->visible(false)->content('N/A'),
            Column::make('created_at')->className('has-text-right'),
            Column::make('added by', 'createdBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Accounts' . date('YmdHis');
    }
}
