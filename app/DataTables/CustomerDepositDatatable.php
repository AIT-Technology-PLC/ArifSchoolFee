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
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($customerDeposit) => route('customer-deposits.show', $customerDeposit->id),
                'x-data' => 'showRowDetails',
                'x-on:click' => 'showDetails',
            ])
            ->editColumn('customer', function ($customerDeposit) {
                return view('components.datatables.link', [
                    'url' => route('customers.customer-deposits.index', $customerDeposit->customer_id),
                    'label' => $customerDeposit->customer->company_name,
                ]);
            })
            ->editColumn('status', fn($customerDeposit) => view('components.datatables.deposit-status', compact('customerDeposit')))
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
            ->select('customer_deposits.*')
            ->when(request('status') == 'approved', fn($query) => $query->approved())
            ->when(request('status') == 'waiting approval', fn($query) => $query->notApproved())
            ->when(request()->routeIs('customers.customer-deposits.index'), function ($query) {
                return $query->where('customer_id', request()->route('customer')->id);
            })
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'approvedBy:id,name',
                'customer:id,company_name',
            ]);
    }

    protected function getColumns()
    {
        $requestHasCustomer = request()->routeIs('customers.customer-deposits.index');

        return [
            Column::computed('#'),
            Column::make('customer', 'customer.company_name')->className('actions')->visible(!$requestHasCustomer),
            Column::computed('status'),
            Column::make('deposited_at')->className('has-text-right'),
            Column::make('amount'),
            Column::make('bank_name')->visible(false),
            Column::make('reference_number')->visible(false),
            Column::make('attachment')->className('actions')->visible(false),
            Column::make('issued_on')->visible(false),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'CustomerDeposit_' . date('YmdHis');
    }
}
