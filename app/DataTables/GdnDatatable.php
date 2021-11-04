<?php

namespace App\DataTables;

use App\Models\Gdn;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class GdnDatatable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($gdn) => route('gdns.show', $gdn->id),
                'x-data' => 'showRowDetails',
                '@click' => 'showDetails',
            ])
            ->editColumn('do no', fn($gdn) => $gdn->code)
            ->editColumn('status', fn($gdn) => view('components.datatables.gdn-status', compact('gdn')))
            ->editColumn('total price', function ($gdn) {
                return userCompany()->isDiscountBeforeVAT() ?
                userCompany()->currency . '. ' . number_format($gdn->grandTotalPrice, 2) :
                userCompany()->currency . '. ' . number_format($gdn->grandTotalPriceAfterDiscount, 2);
            })
            ->addColumn('customer', fn($gdn) => $gdn->customer->company_name ?? 'N/A')
            ->editColumn('description', fn($gdn) => view('components.datatables.searchable-description', ['description' => $gdn->description]))
            ->editColumn('issued on', fn($gdn) => $gdn->issued_on->toFormattedDateString())
            ->editColumn('prepared by', fn($gdn) => $gdn->createdBy->name)
            ->editColumn('approved by', fn($gdn) => $gdn->approvedBy->name ?? 'N/A')
            ->editColumn('edited by', fn($gdn) => $gdn->updatedBy->name)
            ->editColumn('actions', function ($gdn) {
                return view('components.common.action-buttons', [
                    'model' => 'gdns',
                    'id' => $gdn->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Gdn $gdn)
    {
        return $gdn
            ->newQuery()
            ->select('gdns.*')
            ->with([
                'gdnDetails',
                'createdBy:id,name',
                'updatedBy:id,name',
                'approvedBy:id,name',
                'sale:id,code',
                'customer:id,company_name',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('do no', 'code')->title('DO No'),
            isFeatureEnabled('Sale Management') ? Column::make('receipt no', 'sale.code')->content('N/A') : null,
            Column::computed('status'),
            Column::make('payment_type', 'payment_type'),
            Column::computed('total price'),
            Column::make('customer', 'customer.company_name'),
            Column::make('description', 'description'),
            Column::make('issued on', 'issued_on'),
            Column::make('created_at', 'created_at')->visible(false),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('approved by', 'approvedBy.name'),
            Column::make('edited by', 'updatedBy.name'),
            Column::computed('actions')->className('actions'),
        ];

        return Arr::where($columns, fn($column) => $column != null);
    }

    public function html()
    {
        return $this->builder()
            ->responsive(true)
            ->scrollX(true)
            ->scrollY('500px')
            ->scrollCollapse(true)
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('lBfrtip')
            ->lengthMenu([10, 25, 50, 75, 100])
            ->buttons([
                'colvis', 'excelHtml5', 'print', 'pdfHtml5',
            ])
            ->addTableClass('display is-hoverable is-size-7 nowrap')
            ->preDrawCallback("
                function(settings){
                    changeDtButton();
                    $('table').css('display', 'table');
                    removeDtSearchLabel();
                }
            ")
            ->language([
                'processing' => '<i class="fas fa-spinner fa-spin text-green is-size-3"></i>',
            ])
            ->orderBy(isFeatureEnabled('Sale Management') ? 9 : 8, 'desc');
    }

    protected function filename()
    {
        return 'Delivery Orders_' . date('YmdHis');
    }
}
