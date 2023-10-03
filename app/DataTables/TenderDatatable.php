<?php

namespace App\DataTables;

use App\Models\Tender;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TenderDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($tender) => route('tenders.show', $tender->id),
                'x-data' => 'showRowDetails',
                '@click' => 'showDetails',
            ])
            ->editColumn('branch', fn($tender) => $tender->warehouse->name)
            ->editColumn('checklist completion', fn($tender) => $tender->tenderChecklistsCompletionRate . '%')
            ->editColumn('customer', fn($tender) => $tender->customer->company_name ?? 'N/A')
            ->editColumn('price', fn($tender) => view('components.datatables.searchable-description', ['description' => $tender->price]))
            ->editColumn('payment_term', fn($tender) => view('components.datatables.searchable-description', ['description' => $tender->payment_term]))
            ->editColumn('lots', fn($tender) => $tender->tender_lots_count)
            ->editColumn('description', fn($tender) => view('components.datatables.searchable-description', ['description' => $tender->description]))
            ->editColumn('published_on', fn($tender) => $tender->published_on->toFormattedDateString())
            ->editColumn('closing_date', fn($tender) => $tender->closing_date->toDayDateTimeString())
            ->editColumn('opening_date', fn($tender) => $tender->opening_date->toDayDateTimeString())
            ->editColumn('prepared by', fn($tender) => $tender->createdBy->name)
            ->editColumn('edited by', fn($tender) => $tender->updatedBy->name)
            ->editColumn('actions', function ($tender) {
                return view('components.common.action-buttons', [
                    'model' => 'tenders',
                    'id' => $tender->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Tender $tender)
    {
        return $tender
            ->newQuery()
            ->select('tenders.*')
            ->when(is_numeric(request('branch')), fn($query) => $query->where('tenders.warehouse_id', request('branch')))
            ->withCount('tenderLots')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'warehouse:id,name',
                'customer:id,company_name',
                'tenderChecklists',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('branch', 'warehouse.name')->visible(false),
            Column::make('code')->title('Tender No'),
            Column::make('type')->visible(false),
            Column::make('status'),
            Column::computed('checklist completion')->addClass('has-text-centered')->visible(false),
            Column::make('customer', 'customer.company_name'),
            Column::make('participants')->content('N/A')->addClass('has-text-centered')->visible(false),
            Column::make('bid_bond_type')->content('N/A')->visible(false),
            Column::make('bid_bond_amount')->content('N/A')->visible(false),
            Column::make('bid_bond_validity')->content('N/A')->visible(false),
            Column::make('price')->content('N/A')->visible(false),
            Column::make('payment_term')->content('N/A')->visible(false),
            Column::computed('lots')->addClass('has-text-centered')->visible(false),
            Column::make('description')->visible(false),
            Column::make('published_on')->className('has-text-right'),
            Column::make('closing_date')->className('has-text-right'),
            Column::make('opening_date')->className('has-text-right')->visible(false),
            Column::make('prepared by', 'createdBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Tenders_' . date('YmdHis');
    }
}
