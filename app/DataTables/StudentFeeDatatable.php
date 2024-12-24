<?php

namespace App\DataTables;

use App\Models\AssignFeeMaster;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StudentFeeDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    protected $studentId;

    public function __construct($studentId = null)
    {
        $this->studentId = $studentId;
    }

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('fees', fn($assignFeeMaster) => str($assignFeeMaster->feeMaster->feeType->name)->append(' / '.$assignFeeMaster->feeMaster->feeType->feeGroup->name))
            ->editColumn('due_date', fn($assignFeeMaster) => $assignFeeMaster->feeMaster->due_date->toFormattedDateString())
            ->editColumn('amount', fn($assignFeeMaster) => money($assignFeeMaster->feeMaster->amount))
            ->editColumn('status', fn($assignFeeMaster) => view('components.datatables.student-fee-status', compact('assignFeeMaster')))
            ->editColumn('mode', function($assignFeeMaster) {
                if ($assignFeeMaster->feePayments->isNotEmpty()) {
                    $paymentMode = $assignFeeMaster->feePayments->first();
                    return $paymentMode ? $paymentMode->payment_mode: null;
                }
                return null;
            })
            ->editColumn('date', function($assignFeeMaster) {
                if ($assignFeeMaster->feePayments->isNotEmpty()) {
                    $payment = $assignFeeMaster->feePayments->sortByDesc('payment_date')->first();
                    return $payment ? $payment->payment_date->toFormattedDateString() : 'N/A';
                }
                return null;
            })
            ->editColumn('discount', function($assignFeeMaster) {
                if ($assignFeeMaster->feePayments->isNotEmpty()) {
                    $paymentDiscount = $assignFeeMaster->feePayments->first();
                    return $paymentDiscount ? money ($paymentDiscount->discount_amount) : '0.00';
                }
                return '0.00';
            })
            ->editColumn('fine', function($assignFeeMaster) {
                if ($assignFeeMaster->feePayments->isNotEmpty()) {
                    $paymentFine = $assignFeeMaster->feePayments->first();
                    return $paymentFine ? money ($paymentFine->fine_amount) : '0.00';
                }
                return '0.00';
            })
            ->editColumn('commission', function($assignFeeMaster) {
                if ($assignFeeMaster->feePayments->isNotEmpty()) {
                    $paymentCommission = $assignFeeMaster->feePayments->first();
                    return $paymentCommission ? money ($paymentCommission->commission_amount) : '0.00';
                }
                return '0.00';
            })
            ->editColumn('paid', function($assignFeeMaster) {
                if ($assignFeeMaster->feePayments->isNotEmpty()) {
                    $paymentPaid = $assignFeeMaster->feePayments->first();
                    $paidAmount = $paymentPaid ? $paymentPaid->amount + $paymentPaid->fine_amount - $paymentPaid->discount_amount : '0.00';

                    if (isCommissionFromPayer($assignFeeMaster->company->id)) {
                        return money($paidAmount + $paymentPaid->commission_amount);
                    }
                    
                    return $paidAmount;
                }

                return '0.00';
            })
            ->editColumn('actions', function ($assignFeeMaster) {
                return view('components.datatables.student-fee-action', compact('assignFeeMaster'));
            })
            ->addIndexColumn();
    }

    public function query(AssignFeeMaster $assignFeeMaster)
    {
        return $assignFeeMaster
            ->newQuery()
            ->select('assign_fee_masters.*')
            ->where('student_id', $this->studentId) 
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'feeMaster',
                'feePayments'
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('invoice_number'),
            Column::make('fees', 'feeMaster.name')->content('N/A')->orderable(false),
            Column::make('due_date', 'feeMaster.due_date'),
            Column::make('status')->orderable(false),
            Column::make('amount', 'feeMaster.amount'),
            Column::make('mode', 'feePayments.payment_mode'),
            Column::make('date', 'feePayments.payment_date'),
            Column::make('fine', 'feePayments.fine_amount')->content('0.00'),
            Column::make('discount', 'feePayments.discount_amount')->content('0.00'),
            Column::make('commission', 'feePayments.commission_amount')->content('0.00'),
            Column::make('paid', 'feePayments.amount')->content('0.00'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Student Fees_' . date('YmdHis');
    }
}
