<?php

namespace App\DataTables\Admin;

use App\Models\PaymentMethod;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PaymentMethodDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->editColumn('name', fn($paymentMethod) => str()->ucfirst($paymentMethod->name))
            ->editColumn('status', fn($paymentMethod) => view('components.datatables.payment-method-status', compact('paymentMethod')))
            ->editColumn('created_at', fn($paymentMethod) => $paymentMethod->created_at->toFormattedDateString())
            ->editColumn('actions', function ($paymentMethod) {
                return view('components.common.action-buttons', [
                    'model' => 'admin.payment-methods',
                    'id' => $paymentMethod->id,
                    'buttons' => ['edit'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(PaymentMethod $paymentMethod)
    {
        return $paymentMethod
            ->newQuery()
            ->select('payment_methods.*');
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('name')->addClass('has-text-weight-bold'),
            Column::make('status')->orderable(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Payment Methods_' . date('YmdHis');
    }
}

