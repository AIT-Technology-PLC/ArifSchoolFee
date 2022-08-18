<?php

namespace App\DataTables;

use App\Models\Debit;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DebitDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($debit) => route('debits.show', $debit->id),
                'x-data' => 'showRowDetails',
                '@click' => 'showDetails',
            ])
            ->editColumn('branch', fn($debit) => $debit->warehouse->name)
            ->editColumn('purchase no', function ($debit) {
                return view('components.datatables.link', [
                    'url' => $debit->purchase()->exists() ? route('purchases.show', $debit->purchase->id) : 'javascript:void(0)',
                    'label' => $debit->purchase()->exists() ? $debit->purchase->code : 'N/A',
                ]);
            })
            ->editColumn('supplier', function ($debit) {
                return view('components.datatables.link', [
                    'url' => route('suppliers.debits.index', $debit->supplier_id),
                    'label' => $debit->supplier->company_name,
                ]);
            })
            ->editColumn('status', fn($debit) => view('components.datatables.debit-status', compact('debit')))
            ->filterColumn('status', function ($query, $keyword) {
                $query
                    ->when(strtolower($keyword) == 'no settlements', fn($query) => $query->noSettlements())
                    ->when(strtolower($keyword) == 'partial settlements', fn($query) => $query->partiallySettled())
                    ->when(strtolower($keyword) == 'settled', fn($query) => $query->settled());
            })
            ->editColumn('debit amount', fn($debit) => userCompany()->currency . '. ' . number_format($debit->debit_amount, 2))
            ->editColumn('amount settled', fn($debit) => userCompany()->currency . '. ' . number_format($debit->debit_amount_settled, 2))
            ->editColumn('amount unsettled', fn($debit) => userCompany()->currency . '. ' . number_format($debit->debit_amount_unsettled, 2))
            ->editColumn('issued_on', fn($debit) => $debit->issued_on->toFormattedDateString())
            ->editColumn('due_date', fn($debit) => $debit->due_date->toFormattedDateString())
            ->editColumn('actions', function ($debit) {
                return view('components.common.action-buttons', [
                    'model' => 'debits',
                    'id' => $debit->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Debit $debit)
    {
        return $debit
            ->newQuery()
            ->select('debits.*')
            ->when(is_numeric(request('branch')), fn($query) => $query->where('debits.warehouse_id', request('branch')))
            ->when(request('status') == 'no settlements', fn($query) => $query->noSettlements())
            ->when(request('status') == 'partial settlements', fn($query) => $query->partiallySettled())
            ->when(request('status') == 'settled', fn($query) => $query->settled())
            ->when(request()->routeIs('suppliers.debits.index'), function ($query) {
                return $query->where('supplier_id', request()->route('supplier')->id);
            })
            ->when(request('type') == 'due', function ($query) {
                return $query->whereRaw('DATEDIFF(due_date, CURRENT_DATE) BETWEEN 1 AND 5');
            })
            ->with([
                'purchase:id,code',
                'supplier:id,company_name',
                'warehouse:id,name',
            ]);
    }

    protected function getColumns()
    {
        $isHidden = isFeatureEnabled('Purchase Management') ? '' : 'is-hidden';
        $requestHasSupplier = request()->routeIs('suppliers.debits.index');

        return [
            Column::computed('#'),
            Column::make('branch', 'warehouse.name')->visible(false),
            Column::make('code')->className('has-text-centered')->title('Credit No'),
            Column::make('purchase no', 'purchase.code')
                ->className('has-text-centered actions ' . $isHidden),
            Column::make('status')->orderable(false),
            Column::make('supplier', 'supplier.company_name')->className('actions')->visible(!$requestHasSupplier),
            Column::make('debit amount', 'debit_amount'),
            Column::make('amount settled', 'debit_amount_settled')->visible(false),
            Column::computed('amount unsettled')->visible(false),
            Column::make('issued_on'),
            Column::make('due_date')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename()
    {
        return 'Debit_' . date('YmdHis');
    }
}
