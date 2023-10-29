<?php

namespace App\DataTables;

use App\Models\CustomField;
use App\Models\Gdn;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class GdnDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($gdn) => route('gdns.show', $gdn->id),
                'x-data' => 'showRowDetails',
                'x-on:click' => 'showDetails',
            ])
            ->customColumns('gdn')
            ->editColumn('branch', fn($gdn) => $gdn->warehouse->name)
            ->editColumn('invoice no', fn($gdn) => $gdn->sale->code ?? 'N/A')
            ->editColumn('status', fn($gdn) => view('components.datatables.gdn-status', compact('gdn')))
            ->editColumn('delivery_status', fn($gdn) => view('components.datatables.delivery-status', ['model' => $gdn]))
            ->filterColumn('status', function ($query, $keyword) {
                $query
                    ->when($keyword == 'waiting approval', fn($query) => $query->notApproved()->notCancelled())
                    ->when($keyword == 'approved', fn($query) => $query->notSubtracted()->notCancelled()->approved())
                    ->when($keyword == 'subtracted', fn($query) => $query->subtracted()->notCancelled()->notClosed())
                    ->when($keyword == 'voided', fn($query) => $query->cancelled())
                    ->when($keyword == 'closed', fn($query) => $query->closed());
            })
            ->editColumn('total price', function ($gdn) {
                return userCompany()->isDiscountBeforeTax() ?
                userCompany()->currency . '. ' . number_format($gdn->grandTotalPrice, 2) :
                userCompany()->currency . '. ' . number_format($gdn->grandTotalPriceAfterDiscount, 2);
            })
            ->editColumn('customer', fn($gdn) => $gdn->customer->company_name ?? 'N/A')
            ->editColumn('contact', fn($gdn) => $gdn->contact->name ?? 'N/A')
            ->editColumn('customer_tin', fn($gdn) => $gdn->customer->tin ?? 'N/A')
            ->editColumn('description', fn($gdn) => view('components.datatables.searchable-description', ['description' => $gdn->description]))
            ->editColumn('issued_on', fn($gdn) => $gdn->issued_on->toFormattedDateString())
            ->editColumn('created_at', fn($gdn) => $gdn->created_at->diffForHumans())
            ->editColumn('prepared by', fn($gdn) => $gdn->createdBy->name)
            ->editColumn('approved by', fn($gdn) => $gdn->approvedBy->name ?? 'N/A')
            ->editColumn('edited by', fn($gdn) => $gdn->updatedBy->name)
            ->editColumn('subtracted by', fn($gdn) => $gdn->subtractedBy->name ?? 'N/A')
            ->editColumn('voided by', fn($gdn) => $gdn->cancelledBy->name ?? 'N/A')
            ->editColumn('actions', function ($gdn) {
                return view('components.common.action-buttons', [
                    'model' => 'gdns',
                    'id' => $gdn->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Gdn $gdn)
    {
        return $gdn
            ->newQuery()
            ->select('gdns.*')
            ->when(is_numeric(request('branch')), fn($query) => $query->where('gdns.warehouse_id', request('branch')))
            ->when(!is_null(request('paymentType')) && request('paymentType') != 'all', fn($query) => $query->where('gdns.payment_type', request('paymentType')))
            ->when(request('status') == 'waiting approval', fn($query) => $query->notApproved()->notCancelled())
            ->when(request('status') == 'approved', fn($query) => $query->notSubtracted()->notCancelled()->approved())
            ->when(request('status') == 'subtracted', fn($query) => $query->subtracted()->notCancelled()->notClosed())
            ->when(request('status') == 'voided', fn($query) => $query->cancelled())
            ->when(request('status') == 'closed', fn($query) => $query->closed())
            ->with([
                'gdnDetails',
                'createdBy:id,name',
                'updatedBy:id,name',
                'approvedBy:id,name',
                'cancelledBy:id,name',
                'subtractedBy:id,name',
                'sale:id,code',
                'customer:id,company_name,tin',
                'contact:id,name',
                'warehouse:id,name',
                'customFieldValues.customField',
            ]);
    }

    protected function getColumns()
    {
        foreach (CustomField::active()->visibleOnColumns()->where('model_type', 'gdn')->pluck('label') as $label) {
            $customFields[] = Column::make($label, 'customFieldValues.value');
        }

        $columns = [
            Column::computed('#'),
            Column::make('branch', 'warehouse.name')->visible(false),
            Column::make('code')->className('has-text-centered')->title('Delivery Order No'),
            isFeatureEnabled('Sale Management') ? Column::make('invoice no', 'sale.code')->visible(false) : null,
            ...($customFields ?? []),
            Column::make('status')->orderable(false),
            userCompany()->isPartialDeliveriesEnabled() ? Column::computed('delivery_status') : null,
            Column::make('payment_type')->visible(false),
            Column::make('bank_name')->visible(false)->content('N/A'),
            Column::make('reference_number')->visible(false)->content('N/A'),
            Column::computed('total price')->visible(false),
            Column::make('customer', 'customer.company_name'),
            Column::make('contact', 'contact.name'),
            Column::make('customer_tin', 'customer.tin')->visible(false)->title('Customer TIN'),
            Column::make('description')->visible(false),
            Column::make('issued_on'),
            Column::make('created_at')->visible(false)->title('Prepared on'),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::make('subtracted by', 'subtractedBy.name')->visible(false),
            Column::make('voided by', 'cancelledBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];

        return Arr::where($columns, fn($column) => $column != null);
    }

    protected function filename(): string
    {
        return 'Delivery Orders_' . date('YmdHis');
    }
}
