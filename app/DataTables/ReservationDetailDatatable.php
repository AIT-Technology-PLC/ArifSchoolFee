<?php

namespace App\DataTables;

use App\Models\ReservationDetail;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ReservationDetailDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('from', fn ($reservationDetail) => $reservationDetail->warehouse->name)
            ->editColumn('product', function ($reservationDetail) {
                return view('components.datatables.product-code', [
                    'product' => $reservationDetail->product->name,
                    'code' => $reservationDetail->product->code ?? '',
                ]);
            })
            ->editColumn('quantity', function ($reservationDetail) {
                return quantity($reservationDetail->quantity, $reservationDetail->product->unit_of_measurement);
            })
            ->editColumn('unit_price', fn ($reservationDetail) => money($reservationDetail->unit_price))
            ->editColumn('discount', fn ($reservationDetail) => $reservationDetail->discount.'%')
            ->editColumn('total', fn ($reservationDetail) => money($reservationDetail->totalPrice))
            ->editColumn('description', fn ($reservationDetail) => nl2br(e($reservationDetail->description)))
            ->editColumn('actions', function ($reservationDetail) {
                return view('components.common.action-buttons', [
                    'model' => 'reservation-details',
                    'id' => $reservationDetail->id,
                    'buttons' => ['delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(ReservationDetail $reservationDetail)
    {
        return $reservationDetail
            ->newQuery()
            ->select('reservation_details.*')
            ->where('reservation_id', request()->route('reservation')->id)
            ->with([
                'warehouse',
                'product',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('from', 'warehouse.name'),
            Column::make('product', 'product.name'),
            Column::make('quantity')->addClass('has-text-right'),
            Column::make('unit_price')->addClass('has-text-right'),
            userCompany()->isDiscountBeforeVAT() ? Column::computed('discount') : null,
            Column::computed('total')->addClass('has-text-right'),
            Column::make('description')->visible(false),
            Column::computed('actions'),
        ];

        return collect($columns)->filter()->toArray();
    }

    protected function filename()
    {
        return 'Reservation Details_'.date('YmdHis');
    }
}
