<?php

namespace App\DataTables;

use App\Models\CustomerDeposit;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CustomerDepositDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('customer', fn($customerDeposit) => $customerDeposit->customer->company_name)
            ->editColumn('status', fn($customerDeposit) => view('components.datatables.deposit-status', compact('customerDeposit')))
            ->filterColumn('status', function ($query, $keyword) {
                $query
                    ->when($keyword == 'waiting approval', fn($query) => $query->notApproved())
                    ->when($keyword == 'approved', fn($query) => $query->approved());
            })
            ->editColumn('issued_on', fn($customerDeposit) => $customerDeposit->issued_on->toFormattedDateString())
            ->editColumn('deposited_at', fn($customerDeposit) => $customerDeposit->deposited_at->toFormattedDateString())
            ->editColumn('amount', fn($customerDeposit) => userCompany()->currency . '. ' . number_format($customerDeposit->amount, 2))
            ->editColumn('bank_name', fn($customerDeposit) => $customerDeposit->bank_name ?? 'N/A')
            ->editColumn('reference_number', fn($customerDeposit) => $customerDeposit->reference_number ?? 'N/A')
            ->editColumn('attachment', function ($customerDeposit) {
                return view('components.datatables.link', [
                    'url' => isset($customerDeposit->attachment) ? asset('/storage/' . $customerDeposit->attachment) : '#',
                    'label' => isset($customerDeposit->attachment) ? 'View Attachment' : 'No Attachment',
                    'target' => '_blank',
                ]);
            })
            ->editColumn('prepared by', fn($customerDeposit) => $customerDeposit->createdBy->name)
            ->editColumn('approved by', fn($customerDeposit) => $customerDeposit->approvedBy->name ?? 'N/A')
            ->editColumn('edited by', fn($customerDeposit) => $customerDeposit->updatedBy->name)
            ->editColumn('actions', function ($customerDeposit) {
                return view('components.common.action-buttons', [
                    'model' => 'customer-deposits',
                    'id' => $customerDeposit->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(CustomerDeposit $customerDeposit)
    {
        return $customerDeposit
            ->newQuery()
            ->when(request('status') == 'approved', fn($query) => $query->approved())
            ->when(request('status') == 'waiting approval', fn($query) => $query->notApproved())
            ->select('customer_deposits.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'approvedBy:id,name',
                'customer:id,company_name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('customer', 'customer.company_name')->className('has-text-centered'),
            Column::make('status')->orderable(false),
            Column::make('issued_on')->className('has-text-right'),
            Column::make('deposited_at')->className('has-text-right'),
            Column::make('amount'),
            Column::make('bank_name'),
            Column::make('reference_number'),
            Column::make('attachment'),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename()
    {
        return 'CustomerDeposit_' . date('YmdHis');
    }
}