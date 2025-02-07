<?php

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentTransaction;
use App\Models\FeePayment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TelebirrPaymentController extends Controller
{
    public function notify(Request $request)
    {
        Log::info('Telebirr Webhook Notify:', [
            'Merch_order_id' => $request->merch_order_id,
            'status' => $request->trade_status
        ]);
        
        // Find the pending transaction with the session ID
        $transaction = PaymentTransaction::pending()->telebirr()->where('session_id', $request['merch_order_id'])->first();

        if (!$transaction) {
            Log::error('Transaction not found for Order Id: ' . $request['merch_order_id']);
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        DB::beginTransaction();

        try {
            switch ($request->trade_status) {
                case 'Completed':
                    $transaction->status = 'success';
                    $paymentData = json_decode($transaction->payment_data, true);
                    $feePayment = new FeePayment();
                    $feePayment->company_id = $transaction->assignFeeMaster->company_id; 
                    $feePayment->student_id = $transaction->assignFeeMaster->student_id;
                    $feePayment->student_history_id = $transaction->assignFeeMaster->student->latestStudentHistoryId();
                    $feePayment->assign_fee_master_id = $transaction->assignFeeMaster->id;
                    $feePayment->payment_mode = 'Telebirr';
                    $feePayment->reference_number = $request->payment_order_id;
                    $feePayment->fee_discount_id = $paymentData['fee_discount_id'] ?? null;
                    $feePayment->payment_date = Carbon::parse($paymentData['payment_date']);
                    $feePayment->amount = $paymentData['amount'];
                    $feePayment->fine_amount = $paymentData['fine_amount'] ?? 0;
                    $feePayment->discount_amount = $paymentData['discount_amount'] ?? 0;
                    $feePayment->commission_amount = $paymentData['commission_amount'] ?? 0;
                    $feePayment->exchange_rate = $paymentData['exchange_rate'] ?? null;
                    $feePayment->discount_month = (isset($paymentData['discount_amount']) && $paymentData['discount_amount'] > 0) || isset($paymentData['fee_discount_id']) ? Carbon::now() : null;

                    $feePayment->save();
                    break;
                default:
                    return response()->json(['message' => 'Transaction status is unknown'], 400);
            }

            $transaction->save();
            DB::commit();
        } 
        catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating payment transaction or fee payment: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing payment'], 500);
        }

        // Return success response after processing the webhook
        return response()->json(['message' => 'Webhook processed successfully'], 200);
    }

    public function redirect($routeId)
    {
        return redirect()->route('collect-fees.show', $routeId)->with('successMessage', 'The transaction was made successfully.');
    }
}
