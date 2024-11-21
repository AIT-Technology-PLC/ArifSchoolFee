<?php

namespace App\DataTables;

use App\Models\FeePayment;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SearchFeePaymentDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('class', fn($feePayment) => $feePayment->student->schoolClass->name)
            ->editColumn('fee group', fn($feePayment) => $feePayment->assignFeeMaster->feeMaster->feeType->name)
            ->editColumn('fee type', fn($feePayment) => $feePayment->assignFeeMaster->feeMaster->feeType->feeGroup->name)
            ->editColumn('name', fn($feePayment) => str($feePayment->student->name)->append(' '.$feePayment->student->father_name))
            ->editColumn('date', fn($feePayment) => $feePayment->payment_date->toFormattedDateString())
            ->editColumn('amount', fn($feePayment) => money($feePayment->amount_))
            ->editColumn('discount', fn($feePayment) => money($feePayment->discount_amount))
            ->editColumn('fine', fn($feePayment) => money($feePayment->fine_amount))
            ->addIndexColumn();
    }

    public function query(FeePayment $feePayment)
    {
        $query = $feePayment->newQuery()->select('fee_payments.*');

        $query->when(is_numeric(request('branch')), fn($q) => $q->whereHas('student', fn($q) => $q->where('warehouse_id', request('branch'))))
        ->when(is_numeric(request('class')), fn($q) => $q->whereHas('student', fn($q) => $q->where('school_class_id', request('class'))))
        ->when(is_numeric(request('section')), fn($q) => $q->whereHas('student', fn($q) => $q->where('section_id', request('section'))))
        ->when(request('other'), function($query) {
            return $query->whereHas('student', function($q) {
                $q->where('first_name', 'like', '%' . request('other') . '%')
                ->orWhere('code', 'like', '%' . request('other') . '%')
                ->orWhere('phone', 'like', '%' . request('other') . '%');
            });
        });

        if (!request('branch') && !request('class') && !request('section') && !request('other')) {
            $query->whereRaw('1 = 0');
        }

        return $query->with([
            'student:id,name',
            'assignFeeMaster:id,name',
        ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('id')->title('Payment Id'),
            Column::make('date')->content(''),
            Column::make('name','student.first_name')->content(''),
            Column::make('class')->content('N/A'),
            Column::make('fee group')->content('N/A'),
            Column::make('fee type')->content('N/A'),
            Column::make('mode')->content(''),
            Column::make('amount')->content('0.00'),
            Column::make('discount')->content('0.00'),
            Column::make('fine')->content('0.00'),
        ];
    }

    protected function filename(): string
    {
        return 'Search Fee Payments_' . date('YmdHis');
    }
}
