<?php

namespace App\DataTables;

use App\Models\AdjustmentDetail;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AdjustmentDetailDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('operation', function ($adjustmentDetail) {
                return ($adjustmentDetail->is_subtract ? 'Subtract From ' : 'Add To ') . $adjustmentDetail->warehouse->name;
            })
            ->editColumn('product', function ($adjustmentDetail) {
                return view('components.datatables.product-code', [
                    'product' => $adjustmentDetail->product->name,
                    'code' => $adjustmentDetail->product->code ?? '',
                ]);
            })
            ->editColumn('quantity', function ($adjustmentDetail) {
                return quantity($adjustmentDetail->quantity, $adjustmentDetail->product->unit_of_measurement);
            })
            ->editColumn('batch_no', fn($adjustmentDetail) => $adjustmentDetail->merchandiseBatch?->batch_no)
            ->editColumn('expires_on', fn($adjustmentDetail) => $adjustmentDetail->merchandiseBatch?->expires_on?->toFormattedDateString())
            ->editColumn('description', fn($adjustmentDetail) => $adjustmentDetail->reason)
            ->editColumn('actions', function ($adjustmentDetail) {
                return view('components.common.action-buttons', [
                    'model' => 'adjustment-details',
                    'id' => $adjustmentDetail->id,
                    'buttons' => ['delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(AdjustmentDetail $adjustmentDetail)
    {
        return $adjustmentDetail
            ->newQuery()
            ->select('adjustment_details.*')
            ->where('adjustment_id', request()->route('adjustment')->id)
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
            Column::make('operation', 'warehouse.name'),
            Column::make('product', 'product.name'),
            Column::make('quantity'),
            Column::make('batch_no', 'merchandiseBatch.batch_no')->content('N/A')->addClass('has-text-right')->visible(false),
            Column::make('expires_on', 'merchandiseBatch.expires_on')->title('Expiry Date')->content('N/A')->addClass('has-text-right')->visible(false),
            Column::make('reason'),
            Column::computed('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Adjustment Details_' . date('YmdHis');
    }
}
