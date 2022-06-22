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
            ->editColumn('bill Of Material', fn($jobDetail) => $jobDetail->billOfMaterial->name)
            ->editColumn('status', fn($jobDetail) => view('components.datatables.job-detail-status', compact('jobDetail')))
            ->editColumn('work In Process', fn($jobDetail) => $jobDetail->wip)
            ->editColumn('available', fn($jobDetail) => $jobDetail->available)
            ->editColumn('quantity', fn($jobDetail) => $jobDetail->quantity)
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
            ->where('job_id', request()->route('job')->id)
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
            Column::make('bill Of Material', 'billOfMaterial.name'),
            Column::make('status')->orderable(false),
            Column::make('quantity'),
            Column::make('work In Process'),
            Column::make('available')->title('Finished Goods'),
            Column::computed('actions'),
        ];
    }

    protected function filename()
    {
        return 'JobDetail_' . date('YmdHis');
    }
}
