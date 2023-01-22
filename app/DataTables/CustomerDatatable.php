<?php

namespace App\DataTables;

use App\Models\Customer;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CustomerDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('credit limit', function ($customer) {
                return isFeatureEnabled('Credit Management')
                ? view('components.datatables.link', [
                    'url' => route('customers.credits.index', $customer->id),
                    'label' => userCompany()->currency . '. ' . number_format($customer->credit_amount_limit, 2),
                ])
                : userCompany()->currency . '. ' . number_format($customer->credit_amount_limit, 2);
            })
            ->editColumn('registered on', fn($customer) => $customer->created_at->toFormattedDateString())
            ->editColumn('business_licence', function ($customer) {
                return view('components.datatables.link', [
                    'url' => isset($customer->business_licence) ? asset('/storage/' . $customer->business_licence) : '#',
                    'label' => isset($customer->business_licence) ? 'View Licence' : 'No Licence',
                    'target' => '_blank',
                ]);
            })
            ->editColumn('document_expire_on', fn($customer) => $customer->document_expire_on?->toDateString())
            ->editColumn('added by', fn($customer) => $customer->createdBy->name)
            ->editColumn('edited by', fn($customer) => $customer->updatedBy->name)
            ->editColumn('actions', function ($customer) {
                return view('components.datatables.customer-action', compact('customer'));
            })
            ->addIndexColumn();
    }

    public function query(Customer $customer)
    {
        return $customer->newQuery()
            ->select('customers.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('company_name')->title('Name'),
            Column::make('tin')->content('N/A')->title('TIN No'),
            Column::make('credit limit', 'credit_amount_limit'),
            Column::make('address')->visible(false)->content('N/A'),
            Column::make('phone')->content('N/A'),
            Column::make('contact_name')->content('N/A'),
            Column::make('email')->visible(false)->content('N/A'),
            Column::make('country')->visible(false)->content('N/A')->title('Country/City'),
            Column::make('registered on', 'created_at')->visible(false),
            Column::make('business_licence')->visible(false),
            Column::make('document_expire_on')->visible(false)->content('N/A'),
            Column::make('added by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename()
    {
        return 'Customer_' . date('YmdHis');
    }
}
