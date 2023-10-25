<?php

namespace App\DataTables;

use App\Models\CustomField;
use App\Models\Purchase;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PurchaseDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($purchase) => route('purchases.show', $purchase->id),
                'x-data' => 'showRowDetails',
                'x-on:click' => 'showDetails',
            ])
            ->customColumns('purchase')
            ->editColumn('branch', fn($purchase) => $purchase->warehouse->name)
            ->editColumn('status', fn($purchase) => view('components.datatables.purchase-status', compact('purchase')))
            ->filterColumn('status', function ($query, $keyword) {
                $query
                    ->when($keyword == 'waiting-approval', fn($query) => $query->notRejected()->notApproved())
                    ->when($keyword == 'approved', fn($query) => $query->notCancelled()->notPurchased()->approved())
                    ->when($keyword == 'purchased', fn($query) => $query->purchased())
                    ->when($keyword == 'rejected', fn($query) => $query->rejected())
                    ->when($keyword == 'cancelled', fn($query) => $query->cancelled());
            })
            ->editColumn('total cost', function ($purchase) {
                return money($purchase->isImported() ? $purchase->purchaseDetails->sum('totalCostAfterTax') : $purchase->grandTotalPriceAfterDiscount);
            })
            ->editColumn('supplier', fn($purchase) => $purchase->supplier->company_name ?? 'N/A')
            ->editColumn('contact', fn($purchase) => $purchase->contact->name ?? 'N/A')
            ->editColumn('description', fn($purchase) => view('components.datatables.searchable-description', ['description' => $purchase->description]))
            ->editColumn('purchased_on', fn($purchase) => $purchase->purchased_on->toFormattedDateString())
            ->editColumn('prepared by', fn($purchase) => $purchase->createdBy->name)
            ->editColumn('approved by', fn($purchase) => $purchase->approvedBy->name ?? 'N/A')
            ->editColumn('rejected by', fn($purchase) => $purchase->rejectedBy->name ?? 'N/A')
            ->editColumn('cancelled by', fn($purchase) => $purchase->cancelledBy->name ?? 'N/A')
            ->editColumn('edited by', fn($purchase) => $purchase->updatedBy->name)
            ->editColumn('actions', function ($purchase) {
                return view('components.common.action-buttons', [
                    'model' => 'purchases',
                    'id' => $purchase->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Purchase $purchase)
    {
        return $purchase
            ->newQuery()
            ->select('purchases.*')
            ->when(is_numeric(request('branch')), fn($query) => $query->where('purchases.warehouse_id', request('branch')))
            ->when(request('status') == 'waiting approval', fn($query) => $query->notRejected()->notApproved())
            ->when(request('status') == 'approved', fn($query) => $query->notCancelled()->notPurchased()->approved())
            ->when(request('status') == 'purchased', fn($query) => $query->purchased())
            ->when(request('status') == 'rejected', fn($query) => $query->rejected())
            ->when(request('status') == 'cancelled', fn($query) => $query->cancelled())

            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'approvedBy:id,name',
                'rejectedBy:id,name',
                'cancelledBy:id,name',
                'supplier:id,company_name',
                'contact:id,name',
                'warehouse:id,name',
                'customFieldValues.customField',
            ]);
    }

    protected function getColumns()
    {
        foreach (CustomField::active()->visibleOnColumns()->where('model_type', 'purchase')->pluck('label') as $label) {
            $customFields[] = Column::make($label, 'customFieldValues.value');
        }

        return Arr::whereNotNull([
            Column::computed('#'),
            Column::make('branch', 'warehouse.name')->visible(false),
            Column::make('code')->className('has-text-centered')->title('Purchase No'),
            Column::make('type')->visible(false),
            ...($customFields ?? []),
            Column::make('status')->orderable(false),
            Column::computed('total cost')->addClass('has-text-right'),
            Column::make('supplier', 'supplier.company_name')->visible(false),
            Column::make('contact', 'contact.name')->visible(false),
            Column::make('description')->visible(false),
            Column::make('purchased_on')->className('has-text-right'),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::make('rejected by', 'rejectedBy.name')->visible(false),
            Column::make('cancelled by', 'cancelledBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ]);
    }

    protected function filename(): string
    {
        return 'Purchases_' . date('YmdHis');
    }
}
