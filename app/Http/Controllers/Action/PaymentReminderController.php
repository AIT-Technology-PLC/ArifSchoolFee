<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\AssignFeeMaster;
use App\Utilities\Sms;

class PaymentReminderController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Collect Fee');
    }

    public function sendPaymentReminder(AssignFeeMaster $assignFeeMaster)
    {
        if ($assignFeeMaster) {

            $message = "Dear Parent,\n\n" .
                       "We hope you are well. Below are the payment details for your child, " . 
                       $assignFeeMaster->student->first_name . ", for the " . 
                       $assignFeeMaster->feeMaster->feeType->name . " fee:\n\n" .
                       "Step #1: Please send the payment to:\n" .
                       "Amount: " . money($assignFeeMaster->feeMaster->amount) . "\n" .
                       "Due_Date: " . $assignFeeMaster->feeMaster->due_date->toDateString() . "\n" .
                       "Telebirr\n" .
                       "Mikiyas Leul\n" .
                       "0933624757\n\n" .
                       "Step #2: Confirm your payment following this link: " .
                       "aitschoolpayment.com/i/" . $assignFeeMaster->invoice_number . "\n\n" .

                       "Thank you for your attention to this matter.\n" .
                       "Best regards,\n" .
                       $assignFeeMaster->company->name;

            Sms::sendSingleMessage(str($assignFeeMaster->student->phone)->after('0')->prepend('00251')->toString(), $message);
            
            return redirect()->back()->with('successMessage','Payment Notice sent successfully');
        }

        return redirect()->back()->with('failedMessage','Unable to send Payment Code. Please Try Again');
    }
}
