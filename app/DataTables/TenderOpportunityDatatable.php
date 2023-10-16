<?php

namespace App\DataTables;

use App\Models\TenderOpportunity;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TenderOpportunityDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($tenderOpportunity) => route('tender-opportunities.show', $tenderOpportunity->id),
                'x-data' => 'showRowDetails',
                'x-on:click' => 'showDetails',
            ])
            ->editColumn('customer', fn($tenderOpportunity) => $tenderOpportunity->customer->company_name ?? 'N/A')
            ->editColumn('status', fn($tenderOpportunity) => $tenderOpportunity->tenderStatus->status ?? 'N/A')
            ->editColumn('published_on', fn($tenderOpportunity) => $tenderOpportunity->published_on->toFormattedDateString())
            ->editColumn('price', function ($tenderOpportunity) {
                return money($tenderOpportunity->price, $tenderOpportunity->currency);
            })
            ->editColumn('prepared by', fn($tenderOpportunity) => $tenderOpportunity->createdBy->name)
            ->editColumn('actions', function ($tenderOpportunity) {
                return view('components.common.action-buttons', [
                    'model' => 'tender-opportunities',
                    'id' => $tenderOpportunity->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(TenderOpportunity $tenderOpportunity)
    {
        return $tenderOpportunity
            ->newQuery()
            ->select('tender_opportunities.*')
            ->with([
                'customer:id,company_name',
                'tenderStatus:id,status',
                'createdBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('code')->title('Ref No'),
            Column::make('status', 'tenderStatus.status'),
            Column::make('customer', 'customer.company_name'),
            Column::make('source')->visible(false),
            Column::make('price', 'price')->visible(false),
            Column::make('published_on'),
            Column::make('prepared by', 'createdBy.name'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'TenderOpportunity_' . date('YmdHis');
    }
}
