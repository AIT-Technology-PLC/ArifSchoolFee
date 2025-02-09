<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AssignFeeMaster;
use App\Utilities\Sms;
use Illuminate\Http\Request;

class PaymentReminderController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Collect Fee');
    }

    public function sendPaymentReminder(Request $request, AssignFeeMaster $assignFeeMaster)
    {
        $request->validate([
            'other_phone' => 'nullable|numeric|',
        ]);

        //calculate the final price 
        $baseAmount = (float) $assignFeeMaster->feeMaster->amount + (float) $assignFeeMaster->getFineAmount();
        $commissionAmount = 0;

        if (isCommissionFromPayer($assignFeeMaster->company->id)) {
            $commissionAmount = calculateCommission($baseAmount, $assignFeeMaster->company->id);
        }

        $finalPrice = $baseAmount + $commissionAmount;

        //check and retrive the active accounts for payemnt
        $accounts = Account::active()->get();
        
        $accountDetails = $accounts->map(function ($account, $index) {
            return "Option " . ($index + 1) . ":\n" .
                    "{$account->account_type}\n" .
                    "Account Number: {$account->account_number}\n" .
                    "Account Holder: {$account->account_holder}";
        })->implode("\n\n"); 

        if ($assignFeeMaster) { 
            $message = "Dear Parent,\n\n" .
                       "We hope you are well. Below are the payment details for your child, " . 
                       $assignFeeMaster->student->first_name . ", for the " . 
                       $assignFeeMaster->feeMaster->feeType->name . " fee:\n\n" .
                       "Payment Id: " . $assignFeeMaster->invoice_number . "\n\n" .
                       "Step #1: Please send the payment to:\n" .
                       "Amount: " . currencyValue($assignFeeMaster->company->id). ', ' . $finalPrice."\n" .
                       "Due_Date: " . $assignFeeMaster->feeMaster->due_date->toDateString() . "\n" .
                        $accountDetails . "\n\n" .

                       "Thank you for your attention to this matter.\n" .
                       "Best regards,\n" .
                       $assignFeeMaster->company->name;

            Sms::sendSingleMessage(str($request->other_phone ?? $assignFeeMaster->student->phone)->after('0')->prepend('00251')->toString(), $message);
            
            return redirect()->back()->with('successMessage','Payment Notice sent successfully');
        }

        return redirect()->back()->with('failedMessage','Unable to send Payment Code. Please Try Again');
    }
}
