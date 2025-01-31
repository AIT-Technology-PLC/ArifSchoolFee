<?php

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use App\Models\FeePayment;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ArifPayController extends Controller
{
    public function callback(Request $request)
    {
        Log::info('ArifPay Webhook Callback:', [
            'sessionId' => $request->sessionId,
            'status' => $request->transactionStatus
        ]);

        // Find the pending transaction with the session ID
        $transaction = PaymentTransaction::pending()->arifPay()->where('session_id', $request->sessionId)->first();

        if (!$transaction) {
            Log::error('Transaction not found for session Id: ' . $request->sessionId);
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        DB::beginTransaction();

        try {
            switch ($request->transactionStatus) {
                case 'SUCCESS':
                    $transaction->status = 'success';
                    $paymentData = json_decode($transaction->payment_data, true);

                    $feePayment = new FeePayment();
                    $feePayment->company_id = $transaction->assignFeeMaster->company_id; 
                    $feePayment->student_id = $transaction->assignFeeMaster->student_id;
                    $feePayment->student_history_id = $transaction->assignFeeMaster->student->latestStudentHistoryId();
                    $feePayment->assign_fee_master_id = $transaction->assignFeeMaster->id;
                    $feePayment->payment_mode = 'Arifpay';
                    $feePayment->fee_discount_id = $paymentData['fee_discount_id'] ?? null;
                    $feePayment->payment_date = Carbon::parse($paymentData['payment_date']);
                    $feePayment->amount = $paymentData['amount'];
                    $feePayment->fine_amount = $paymentData['fine_amount'] ?? 0;
                    $feePayment->discount_amount = $paymentData['discount_amount'] ?? 0;
                    $feePayment->commission_amount = $paymentData['commission_amount'] ?? 0;
                    $feePayment->discount_month = (isset($paymentData['discount_amount']) && $paymentData['discount_amount'] > 0) || isset($paymentData['fee_discount_id']) ? Carbon::now() : null;

                    $feePayment->save();
                    break;
                case 'FAILED':
                    $transaction->status = 'failed';
                    $this->cancelCheckoutSession($request->sessionId);
                    break;
                case 'UNAUTHORIZED':
                    $transaction->status = 'unauthorized';
                    $this->cancelCheckoutSession($request->sessionId);
                    break;
                case 'CANCELLED':
                    $transaction->status = 'canceled';
                    $this->cancelCheckoutSession($request->sessionId);
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

    protected function cancelCheckoutSession(string $sessionId)
    {
        try {
            $url = "https://gateway.arifpay.net/api/sandbox/checkout/session/{$sessionId}";

            $response = Http::post($url);

            if ($response->successful()) {
                Log::info('Checkout session cancelled successfully for session ID: ' . $sessionId);
            } else {
                Log::error('Failed to cancel checkout session for session ID: ' . $sessionId . ' Response: ' . $response->body());
            }
        } catch (Exception $e) {
            Log::error('Error cancelling checkout session: ' . $e->getMessage());
        }
    }

    public function successSession($routeId)
    {
        return redirect()->route('collect-fees.show', $routeId)->with('successMessage', 'The transaction was made successfully.');
    }

    public function cancelSession($routeId)
    {
        return redirect()->route('collect-fees.show', $routeId)->with('failedMessage', 'The transaction has been canceled.');
    }

    public function errorSession($routeId)
    {
        return redirect()->route('collect-fees.show', $routeId)->with('failedMessage', 'There was an error processing the payment.');
    }
}