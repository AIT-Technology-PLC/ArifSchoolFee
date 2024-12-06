<?php

namespace App\Utilities;

use App\Models\AssignFeeMaster;
use App\Models\FeePayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ChequePayment
{
    public function process(AssignFeeMaster $assignFeeMaster, array $paymentData)
    {
        $transaction = $this->storeTransaction($assignFeeMaster, $paymentData);

        return $transaction;
    }

    protected function storeTransaction(AssignFeeMaster $assignFeeMaster, array $paymentData)
    {

        DB::beginTransaction();

        try {
            $transaction = new FeePayment();

            $transaction->company_id = $assignFeeMaster->company_id; 
            $transaction->student_id = $assignFeeMaster->student_id;
            $transaction->fee_discount_id = $paymentData['fee_discount_id'] ?? null;
            $transaction->assign_fee_master_id = $assignFeeMaster->id;
            $transaction->payment_mode = 'Cheque';
            $transaction->payment_date = Carbon::parse($paymentData['payment_date']);
            $transaction->amount = $paymentData['amount'];
            $transaction->fine_amount = $paymentData['fine_amount'] ?? 0;
            $transaction->discount_amount = $paymentData['discount_amount'] ?? 0;
            $transaction->discount_month = isset($paymentData['discount_amount']) && $paymentData['discount_amount']> 0 || isset($paymentData['fee_discount_id']) ? Carbon::now() : null;

            $transaction->save();

            DB::commit();

            return $transaction;

        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }
    }
}