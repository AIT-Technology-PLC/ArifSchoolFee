<?php

namespace App\DataTables;

use App\Models\JobDetail;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class JobDetailDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('product', fn($jobDetail) => $jobDetail->product->name)
            ->editColumn('bill of material', fn($jobDetail) => view('components.datatables.link', [
                'url' => route('bill-of-materials.show', $jobDetail->bill_of_material_id),
                'label' => $jobDetail->billOfMaterial->name,
            ]))
            ->editColumn('status', fn($jobDetail) => view('components.datatables.job-detail-status', compact('jobDetail')))
            ->editColumn('actions', function ($jobDetail) {
                return view('components.common.action-buttons', [
                    'model' => 'job-details',
                    'id' => $jobDetail->id,
                    'buttons' => ['delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(JobDetail $jobDetail)
    {
        return $jobDetail
            ->newQuery()
            ->select('job_details.*')
            ->where('job_order_id', request()->route('job')->id)
            ->with([
                'product',
                'billOfMaterial:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('product', 'product.name'),
            Column::make('bill of material', 'billOfMaterial.name'),
            Column::computed('status')->orderable(false),
            Column::make('quantity'),
            Column::make('wip')->title('Work In Process'),
            Column::make('available')->title('Finished Goods'),
            Column::computed('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'JobDetail_' . date('YmdHis');
    }
}
