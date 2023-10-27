<?php

namespace App\DataTables;

use App\Models\Credit;
use App\Models\CustomField;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CreditDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($credit) => route('credits.show', $credit->id),
                'x-data' => 'showRowDetails',
                'x-on:click' => 'showDetails',
            ])
            ->customColumns('credit')
            ->editColumn('branch', fn($credit) => $credit->warehouse->name)
            ->editColumn('transaction no', function ($credit) {
                return view('components.datatables.link', [
                    'url' => !is_null($credit->creditable_id) ? route($credit->creditable->getTable() . '.show', $credit->creditable_id) : 'javascript:void(0)',
                    'label' => !is_null($credit->creditable_id) ? $credit->creditable->code : 'N/A',
                ]);
            })
            ->editColumn('customer', function ($credit) {
                return view('components.datatables.link', [
                    'url' => route('customers.credits.index', $credit->customer_id),
                    'label' => $credit->customer->company_name,
                ]);
            })
            ->editColumn('status', fn($credit) => view('components.datatables.credit-status', compact('credit')))
            ->filterColumn('status', function ($query, $keyword) {
                $query
                    ->when(strtolower($keyword) == 'no settlements', fn($query) => $query->noSettlements())
                    ->when(strtolower($keyword) == 'partial settlements', fn($query) => $query->partiallySettled())
                    ->when(strtolower($keyword) == 'settled', fn($query) => $query->settled());
            })
            ->editColumn('credit amount', fn($credit) => userCompany()->currency . '. ' . number_format($credit->credit_amount, 2))
            ->editColumn('amount settled', fn($credit) => userCompany()->currency . '. ' . number_format($credit->credit_amount_settled, 2))
            ->editColumn('amount unsettled', fn($credit) => userCompany()->currency . '. ' . number_format($credit->credit_amount_unsettled, 2))
            ->editColumn('issued_on', fn($credit) => $credit->issued_on->toFormattedDateString())
            ->editColumn('due_date', fn($credit) => $credit->due_date?->toFormattedDateString() ?? 'N/A')
            ->editColumn('actions', function ($credit) {
                return view('components.common.action-buttons', [
                    'model' => 'credits',
                    'id' => $credit->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Credit $credit)
    {
        return $credit
            ->newQuery()
            ->select('credits.*')
            ->when(is_numeric(request('branch')), fn($query) => $query->where('credits.warehouse_id', request('branch')))
            ->when(request('status') == 'no settlements', fn($query) => $query->noSettlements())
            ->when(request('status') == 'partial settlements', fn($query) => $query->partiallySettled())
            ->when(request('status') == 'settled', fn($query) => $query->settled())
            ->when(request()->routeIs('customers.credits.index'), function ($query) {
                return $query->where('customer_id', request()->route('customer')->id);
            })
            ->when(request('type') == 'due', function ($query) {
                return $query->whereRaw('DATEDIFF(due_date, CURRENT_DATE) BETWEEN 1 AND 5');
            })
            ->with([
                'creditable',
                'customer:id,company_name',
                'warehouse:id,name',
                'customFieldValues.customField',
            ]);
    }

    protected function getColumns()
    {
        $requestHasCustomer = request()->routeIs('customers.credits.index');

        foreach (CustomField::active()->visibleOnColumns()->where('model_type', 'credit')->pluck('label') as $label) {
            $customFields[] = Column::make($label, 'customFieldValues.value');
        }

        return Arr::whereNotNull([
            Column::computed('#'),
            Column::make('branch', 'warehouse.name')->visible(false),
            Column::make('code')->className('has-text-centered')->title('Credit No'),
            Column::computed('transaction no')->className('has-text-centered actions'),
            ...($customFields ?? []),
            Column::make('status')->orderable(false),
            Column::make('customer', 'customer.company_name')->className('actions')->visible(!$requestHasCustomer),
            Column::make('credit amount', 'credit_amount'),
            Column::make('amount settled', 'credit_amount_settled')->visible(false),
            Column::computed('amount unsettled')->visible(false),
            Column::make('issued_on'),
            Column::make('due_date')->visible(false),
            Column::computed('actions')->className('actions'),
        ]);
    }

    protected function filename(): string
    {
        return 'Credit_' . date('YmdHis');
    }
}
