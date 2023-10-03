<?php

namespace App\DataTables;

use App\Models\Debt;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DebtDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($debt) => route('debts.show', $debt->id),
                'x-data' => 'showRowDetails',
                '@click' => 'showDetails',
            ])
            ->editColumn('branch', fn($debt) => $debt->warehouse->name)
            ->editColumn('purchase no', function ($debt) {
                return view('components.datatables.link', [
                    'url' => $debt->purchase()->exists() ? route('purchases.show', $debt->purchase->id) : 'javascript:void(0)',
                    'label' => $debt->purchase()->exists() ? $debt->purchase->code : 'N/A',
                ]);
            })
            ->editColumn('supplier', function ($debt) {
                return view('components.datatables.link', [
                    'url' => route('suppliers.debts.index', $debt->supplier_id),
                    'label' => $debt->supplier->company_name,
                ]);
            })
            ->editColumn('status', fn($debt) => view('components.datatables.debt-status', compact('debt')))
            ->filterColumn('status', function ($query, $keyword) {
                $query
                    ->when(strtolower($keyword) == 'no settlements', fn($query) => $query->noSettlements())
                    ->when(strtolower($keyword) == 'partial settlements', fn($query) => $query->partiallySettled())
                    ->when(strtolower($keyword) == 'settled', fn($query) => $query->settled());
            })
            ->editColumn('debt amount', fn($debt) => userCompany()->currency . '. ' . number_format($debt->debt_amount, 2))
            ->editColumn('amount settled', fn($debt) => userCompany()->currency . '. ' . number_format($debt->debt_amount_settled, 2))
            ->editColumn('amount unsettled', fn($debt) => userCompany()->currency . '. ' . number_format($debt->debt_amount_unsettled, 2))
            ->editColumn('issued_on', fn($debt) => $debt->issued_on->toFormattedDateString())
            ->editColumn('due_date', fn($debt) => $debt->due_date->toFormattedDateString())
            ->editColumn('actions', function ($debt) {
                return view('components.common.action-buttons', [
                    'model' => 'debts',
                    'id' => $debt->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Debt $debt)
    {
        return $debt
            ->newQuery()
            ->select('debts.*')
            ->when(is_numeric(request('branch')), fn($query) => $query->where('debts.warehouse_id', request('branch')))
            ->when(request('status') == 'no settlements', fn($query) => $query->noSettlements())
            ->when(request('status') == 'partial settlements', fn($query) => $query->partiallySettled())
            ->when(request('status') == 'settled', fn($query) => $query->settled())
            ->when(request()->routeIs('suppliers.debts.index'), function ($query) {
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
        $requestHasSupplier = request()->routeIs('suppliers.debts.index');

        return [
            Column::computed('#'),
            Column::make('branch', 'warehouse.name')->visible(false),
            Column::make('code')->className('has-text-centered')->title('Debt No'),
            Column::make('purchase no', 'purchase.code')
                ->className('has-text-centered actions ' . $isHidden),
            Column::make('status')->orderable(false),
            Column::make('supplier', 'supplier.company_name')->className('actions')->visible(!$requestHasSupplier),
            Column::make('debt amount', 'debt_amount'),
            Column::make('amount settled', 'debt_amount_settled')->visible(false),
            Column::computed('amount unsettled')->visible(false),
            Column::make('issued_on'),
            Column::make('due_date')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Debt_' . date('YmdHis');
    }
}
