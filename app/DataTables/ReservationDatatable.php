<?php

namespace App\DataTables;

use App\Models\CustomField;
use App\Models\Reservation;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ReservationDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($reservation) => route('reservations.show', $reservation->id),
                'x-data' => 'showRowDetails',
                'x-on:click' => 'showDetails',
            ])
            ->customColumns('reservation')
            ->editColumn('branch', fn($reservation) => $reservation->warehouse->name)
            ->editColumn('status', fn($reservation) => view('components.datatables.reservation-status', compact('reservation')))
            ->filterColumn('status', function ($query, $keyword) {
                $query
                    ->when($keyword == 'waiting approval', fn($query) => $query->notApproved()->notCancelled())
                    ->when($keyword == 'approved', fn($query) => $query->approved()->notReserved()->notConverted()->notCancelled())
                    ->when($keyword == 'cancelled', fn($query) => $query->cancelled())
                    ->when($keyword == 'reserved', fn($query) => $query->reserved()->notConverted()->notCancelled())
                    ->when($keyword == 'converted', fn($query) => $query->converted()->notCancelled());
            })
            ->editColumn('total price', function ($reservation) {
                return userCompany()->isDiscountBeforeTax()
                ? money($reservation->grandTotalPrice)
                : money($reservation->grandTotalPriceAfterDiscount);
            })
            ->editColumn('customer', fn($reservation) => $reservation->customer->company_name ?? 'N/A')
            ->editColumn('customer_tin', fn($reservation) => $reservation->customer->tin ?? 'N/A')
            ->editColumn('contact', fn($reservation) => $reservation->contact->name ?? 'N/A')
            ->editColumn('description', fn($reservation) => view('components.datatables.searchable-description', ['description' => $reservation->description]))
            ->editColumn('issued_on', fn($reservation) => $reservation->issued_on->toFormattedDateString())
            ->editColumn('expires_on', fn($reservation) => $reservation->expires_on->toFormattedDateString())
            ->editColumn('prepared by', fn($reservation) => $reservation->createdBy->name)
            ->editColumn('approved by', fn($reservation) => $reservation->approvedBy->name ?? 'N/A')
            ->editColumn('edited by', fn($reservation) => $reservation->updatedBy->name)
            ->editColumn('actions', function ($reservation) {
                return view('components.common.action-buttons', [
                    'model' => 'reservations',
                    'id' => $reservation->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Reservation $reservation)
    {
        return $reservation
            ->newQuery()
            ->select('reservations.*')
            ->when(is_numeric(request('branch')), fn($query) => $query->where('reservations.warehouse_id', request('branch')))
            ->when(!is_null(request('paymentType')) && request('paymentType') != 'all', fn($query) => $query->where('reservations.payment_type', request('paymentType')))
            ->when(request('status') == 'waiting approval', fn($query) => $query->notApproved()->notCancelled())
            ->when(request('status') == 'approved', fn($query) => $query->approved()->notReserved()->notConverted()->notCancelled())
            ->when(request('status') == 'cancelled', fn($query) => $query->cancelled())
            ->when(request('status') == 'reserved', fn($query) => $query->reserved()->notConverted()->notCancelled())
            ->when(request('status') == 'converted', fn($query) => $query->converted()->notCancelled())
            ->with([
                'reservationDetails',
                'createdBy:id,name',
                'updatedBy:id,name',
                'approvedBy:id,name',
                'customer:id,company_name,tin',
                'contact:id,name',
                'warehouse:id,name',
                'customFieldValues.customField',
            ]);
    }

    protected function getColumns()
    {
        foreach (CustomField::active()->visibleOnColumns()->where('model_type', 'reservation')->pluck('label') as $label) {
            $customFields[] = Column::make($label, 'customFieldValues.value');
        }

        return Arr::whereNotNull([
            Column::computed('#'),
            Column::make('branch', 'warehouse.name')->visible(false),
            Column::make('code')->className('has-text-centered')->title('Reservation No'),
            ...($customFields ?? []),
            Column::make('status')->orderable(false),
            Column::make('payment_type')->visible(false),
            Column::computed('total price')->visible(false),
            Column::make('customer', 'customer.company_name'),
            Column::make('customer_tin', 'customer.tin')->visible(false)->title('Customer TIN'),
            Column::make('contact', 'contact.name'),
            Column::make('description')->visible(false),
            Column::make('issued_on'),
            Column::make('expires_on'),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ]);
    }

    protected function filename(): string
    {
        return 'Reservations_' . date('YmdHis');
    }
}
