<?php

namespace App\DataTables;

use App\Models\DamageDetail;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DamageDetailDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('from', fn($damageDetail) => $damageDetail->warehouse->name)
            ->editColumn('product', function ($damageDetail) {
                return view('components.datatables.product-code', [
                    'product' => $damageDetail->product->name,
                    'code' => $damageDetail->product->code ?? '',
                ]);
            })
            ->editColumn('quantity', function ($damageDetail) {
                return quantity($damageDetail->quantity, $damageDetail->product->unit_of_measurement);
            })
            ->editColumn('batch_no', fn($damageDetail) => $damageDetail->merchandiseBatch?->batch_no)
            ->editColumn('expires_on', fn($damageDetail) => $damageDetail->merchandiseBatch?->expires_on?->toFormattedDateString())
            ->editColumn('description', fn($damageDetail) => nl2br(e($damageDetail->description)))
            ->editColumn('actions', function ($damageDetail) {
                return view('components.common.action-buttons', [
                    'model' => 'damage-details',
                    'id' => $damageDetail->id,
                    'buttons' => ['delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(DamageDetail $damageDetail)
    {
        return $damageDetail
            ->newQuery()
            ->select('damage_details.*')
            ->where('damage_id', request()->route('damage')->id)
            ->with([
                'warehouse',
                'product',
                'merchandiseBatch',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('from', 'warehouse.name'),
            Column::make('product', 'product.name'),
            Column::make('quantity'),
            Column::make('batch_no', 'merchandiseBatch.batch_no')->content('N/A')->addClass('has-text-right')->visible(false),
            Column::make('expires_on', 'merchandiseBatch.expires_on')->title('Expiry Date')->content('N/A')->addClass('has-text-right')->visible(false),
            Column::make('description')->visible(false),
            Column::computed('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Damage Details_' . date('YmdHis');
    }
}
