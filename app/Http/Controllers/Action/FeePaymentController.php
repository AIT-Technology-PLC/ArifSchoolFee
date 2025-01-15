<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckPaymentRequest;
use App\Models\AssignFeeMaster;
use App\Utilities\ArifPayPayment;
use App\Utilities\TelebirrPayment;
use App\Utilities\CashPayment;
use App\Utilities\ChequePayment;

class FeePaymentController extends Controller
{
    private $cashPayment, $arifPayPayment, $chequePayment, $telebirrPayment;

    public function __construct(CashPayment $cashPayment, ArifPayPayment $arifPayPayment, ChequePayment $chequePayment, TelebirrPayment $telebirrPayment)
    {
        $this->cashPayment = $cashPayment;
        $this->arifPayPayment = $arifPayPayment;
        $this->chequePayment = $chequePayment;
        $this->telebirrPayment = $telebirrPayment;
    }

    public function processPayment(CheckPaymentRequest $request, AssignFeeMaster $assignFeeMaster)
    {
        $discountAmount = $request->fee_discount_id === null ? 0 : $request->discount_amount;

        $request->merge([
            'amount' => $assignFeeMaster->feeMaster->amount,
            'fine_amount' => $assignFeeMaster->getFineAmount(),
            'discount_amount' => $discountAmount,
            'commission_amount' => calculateCommission(($assignFeeMaster->feeMaster->amount + $assignFeeMaster->getFineAmount() - $discountAmount) , $assignFeeMaster->company->id),
        ]);

        $paymentMethod = $request->input('payment_mode');
        $paymentProcessor = null;

        switch ($paymentMethod) {
            case 'Cash':
                $paymentProcessor = $this->cashPayment;
                break;
            case 'Cheque':
                $paymentProcessor = $this->chequePayment;
                break;
            case 'Arifpay':
                $paymentProcessor = $this->arifPayPayment;
                break;
            case 'Telebirr':
                $paymentProcessor = $this->telebirrPayment;
                break;
            default:
                 return redirect()->back()->with('failedMessage', 'Invalid payment method');
        }

        try {
            $paymentUrl = $paymentProcessor->process($assignFeeMaster, $request->all());
    
            if ($paymentUrl) {
                if ($paymentMethod === 'Arifpay') {
                    return redirect()->away($paymentUrl);
                }elseif ($paymentMethod === 'Telebirr') {
                    return redirect()->away($paymentUrl);
                }

                return redirect()->back()->with('successMessage', 'Payment processed successfully!');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('failedMessage', 'Payment process failed. Please try again.');
        }
    }
}
