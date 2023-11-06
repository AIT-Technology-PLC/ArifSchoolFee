<?php

namespace App\DataTables;

use App\Models\CustomField;
use App\Models\Exchange;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ExchangeDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($exchange) => route('exchanges.show', $exchange->id),
                'x-data' => 'showRowDetails',
                'x-on:click' => 'showDetails',
            ])
            ->customColumns('exchange')
            ->editColumn('status', fn($exchange) => view('components.datatables.exchange-status', compact('exchange')))
            ->filterColumn('status', function ($query, $keyword) {
                $query
                    ->when($keyword == 'waiting approval', fn($query) => $query->notApproved())
                    ->when($keyword == 'approved', fn($query) => $query->approved()->notExecuted())
                    ->when($keyword == 'executed', fn($query) => $query->executed());
            })
            ->editColumn('created_at', fn($exchange) => $exchange->created_at->diffForHumans())
            ->editColumn('prepared by', fn($exchange) => $exchange->createdBy->name)
            ->editColumn('approved by', fn($exchange) => $exchange->approvedBy->name ?? 'N/A')
            ->editColumn('executed by', fn($exchange) => $exchange->executedBy->name ?? 'N/A')
            ->editColumn('edited by', fn($exchange) => $exchange->updatedBy->name)
            ->editColumn('actions', function ($exchange) {
                return view('components.common.action-buttons', [
                    'model' => 'exchanges',
                    'id' => $exchange->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Exchange $exchange)
    {
        return $exchange
            ->newQuery()
            ->select('exchanges.*')
            ->when(is_numeric(request('branch')), fn($query) => $query->where('gdns.warehouse_id', request('branch')))
            ->when(request('status') == 'waiting approval', fn($query) => $query->notApproved())
            ->when(request('status') == 'approved', fn($query) => $query->notExecuted()->approved())
            ->when(request('status') == 'executed', fn($query) => $query->executed())
            ->with([
                'exchangeDetails',
                'createdBy:id,name',
                'updatedBy:id,name',
                'approvedBy:id,name',
                'executedBy:id,name',
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
            Column::make('status')->orderable(false),
            Column::make('created_at')->visible(false)->title('Prepared on'),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::make('executed by', 'executedBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];

        return Arr::where($columns, fn($column) => $column != null);
    }

    protected function filename(): string
    {
        return 'Exchange_' . date('YmdHis');
    }
}
