<?php

namespace App\DataTables\Admin;

use App\Models\PaymentGateway;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PaymentGatewayDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->editColumn('school', fn($paymentGateway) => $paymentGateway->company->name)
            ->editColumn('method', fn($paymentGateway) => $paymentGateway->paymentMethod->name)
            ->editColumn('created_at', fn($paymentGateway) => $paymentGateway->created_at->toFormattedDateString())
            ->editColumn('actions', function ($paymentGateway) {
                return view('components.common.action-buttons', [
                    'model' => 'admin.payment-gateways',
                    'id' => $paymentGateway->id,
                    'buttons' => ['edit'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(PaymentGateway $paymentGateway)
    {
        return $paymentGateway
            ->newQuery()
            ->select('payment_gateways.*')
            ->with([
                'company:id,name',
                'paymentMethod:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('school','company.name'),
            Column::make('method','paymenMethod.name'),
            Column::make('merchant_id'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Payment Gateways_' . date('YmdHis');
    }
}

