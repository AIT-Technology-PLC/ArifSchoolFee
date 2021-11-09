<?php

namespace App\DataTables;

use App\Models\Credit;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Str;
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
                '@click' => 'showDetails',
            ])
            ->editColumn('credit no', fn($credit) => $credit->code)
            ->editColumn('delivery order no', function ($credit) {
                return view('components.datatables.link', [
                    'url' => $credit->gdn()->exists() ? route('gdns.show', $credit->gdn->id) : 'javascript:void(0)',
                    'label' => $credit->gdn()->exists() ? $credit->gdn->code : 'N/A',
                ]);
            })
            ->editColumn('customer', fn($credit) => $credit->customer->company_name)
            ->editColumn('status', fn($credit) => view('components.datatables.credit-status', compact('credit')))
            ->filterColumn('status', function ($query, $keyword) {
                $query
                    ->when(Str::contains('no', $keyword), fn($query) => $query->noSettlements())
                    ->when(Str::contains('partial', $keyword), fn($query) => $query->partiallySettled())
                    ->when(Str::contains('full', $keyword), fn($query) => $query->settled());
            })
            ->editColumn('credit amount', fn($credit) => userCompany()->currency . '. ' . number_format($credit->credit_amount, 2))
            ->editColumn('amount settled', fn($credit) => userCompany()->currency . '. ' . number_format($credit->credit_amount_settled, 2))
            ->editColumn('issued on', fn($credit) => $credit->issued_on->toFormattedDateString())
            ->editColumn('due date', fn($credit) => $credit->due_date->toFormattedDateString())
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
            ->when(request('type') == 'due', fn($query) => $query->whereRaw('DATEDIFF(due_date, CURRENT_DATE) BETWEEN 1 AND 5'))
            ->with([
                'gdn:id,code',
                'customer:id,company_name',
            ]);
    }

    protected function getColumns()
    {
        $isHidden = isFeatureEnabled('Gdn Management') ? '' : 'is-hidden';

        return [
            Column::computed('#'),
            Column::make('credit no', 'code')->className('has-text-centered'),
            Column::make('delivery order no', 'gdn.code')
                ->className('has-text-centered actions ' . $isHidden),
            Column::make('customer', 'customer.company_name'),
            Column::make('status')->orderable(false),
            Column::make('credit amount', 'credit_amount'),
            Column::make('amount settled', 'credit_amount_settled'),
            Column::make('issued on', 'issued_on'),
            Column::make('due date', 'due_date'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename()
    {
        return 'Credit_' . date('YmdHis');
    }
}
