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
                    'label' => money($customer->credit_amount_limit),
                ])
                : money($customer->credit_amount_limit);
            })
            ->editColumn('balance', function ($customer) {
                return isFeatureEnabled('Customer Deposit Management')
                ? view('components.datatables.link', [
                    'url' => route('customers.customer-deposits.index', $customer->id),
                    'label' => money($customer->balance),
                ])
                : money($customer->balance);
            })
            ->editColumn('registered on', fn($customer) => $customer->created_at->toFormattedDateString())
            ->editColumn('business_license_attachment', function ($customer) {
                return view('components.datatables.link', [
                    'url' => isset($customer->business_license_attachment) ? asset('/storage/' . $customer->business_license_attachment) : '#',
                    'label' => isset($customer->business_license_attachment) ? 'View License' : 'No License',
                    'target' => '_blank',
                ]);
            })
            ->editColumn('business_license_expires_on', fn($customer) => $customer->business_license_expires_on)
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
            ->when(request('type') == 'business_license_expiry_due', function ($query) {
                return $query->whereRaw('DATEDIFF(business_license_expires_on, CURRENT_DATE) BETWEEN 1 AND 30');
            })
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        return collect([
            Column::computed('#'),
            Column::make('company_name')->title('Name'),
            Column::make('tin')->content('N/A')->title('TIN No'),
            isFeatureEnabled('Credit Management') ? Column::make('credit limit', 'credit_amount_limit') : null,
            isFeatureEnabled('Customer Deposit Management') ? Column::make('balance')->title('Deposit Balance') : null,
            Column::make('address')->visible(false)->content('N/A'),
            Column::make('phone')->content('N/A'),
            Column::make('contact_name')->content('N/A'),
            Column::make('email')->visible(false)->content('N/A'),
            Column::make('country')->visible(false)->content('N/A')->title('Country/City'),
            Column::make('registered on', 'created_at')->visible(false),
            Column::make('business_license_attachment')->visible(false)->title('Business License'),
            Column::make('business_license_expires_on')->visible(false)->content('N/A')->title('License Expire On'),
            Column::make('added by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ])->filter()->toArray();
    }

    protected function filename()
    {
        return 'Customer_' . date('YmdHis');
    }
}
