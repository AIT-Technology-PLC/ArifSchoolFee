<?php

namespace App\Console\Commands;

use App\Models\Account;
use Illuminate\Console\Command;
use App\Models\AssignFeeMaster;
use App\Utilities\Sms;
use Carbon\Carbon;

class SendPaymentReminderNotifications extends Command
{
    protected $signature = 'assignFeeMaster:payment-reminder-notifications';

    protected $description = 'Send payment reminder notifications based on due dates.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $today = now();
        
        // Fetch all AssignFeeMaster records with unpaid fees
        $assignFeeMasters = AssignFeeMaster::whereDoesntHave('feePayments')
                ->whereHas('feeMaster', function ($query) use ($today) {
                    $query->where('due_date', '>=', $today);
                })->get();

        if ($assignFeeMasters->isEmpty()) {
            $this->info('No payments to remind.');
            return 0;
        }

        foreach ($assignFeeMasters as $assignFeeMaster) {
            $dueDate = $assignFeeMaster->feeMaster->due_date;
            $daysRemaining = $today->diffInDays($dueDate);

            //calculate the final price 
            $baseAmount = (float) $assignFeeMaster->feeMaster->amount + (float) $assignFeeMaster->getFineAmount();
            $commissionAmount = 0;

            if (isCommissionFromPayer($assignFeeMaster->company->id)) {
                $commissionAmount = calculateCommission($baseAmount, $assignFeeMaster->company->id);
            }
            
            $finalPrice = $baseAmount + $commissionAmount;

            //check and retrive the active accounts for payemnt
            $accounts = Account::active()->where('company_id', $assignFeeMaster->company->id)->get();
            $accountDetails = $accounts->map(function ($account, $index) {
                return "Option " . ($index + 1) . ":\n" .
                        "{$account->account_type}\n" .
                        "Account Number: {$account->account_number}\n" .
                        "Account Holder: {$account->account_holder}";
            })->implode("\n\n"); 
            
            if ($assignFeeMaster->feePayments->count() > 0) {
                continue;
            }

            $message = "Dear Parent,\n\n" .
                       "We hope you are well. Below are the payment details for your child, " . 
                       $assignFeeMaster->student->first_name . ", for the " . 
                       $assignFeeMaster->feeMaster->feeType->name . " fee:\n\n" . 
                       "Payment Id: " . $assignFeeMaster->invoice_number . "\n\n" .
                       "Step #1: Please send the payment to:\n" .
                       "Amount: " . $finalPrice . "\n" .
                       "Due_Date: " . $assignFeeMaster->feeMaster->due_date->toDateString() . "\n" .
                       $accountDetails . "\n\n" .
                       
                       "Thank you for your attention to this matter.\n" .
                       "Best regards,\n" .
                       $assignFeeMaster->company->name;

            // Determine the notification interval
            if ($daysRemaining > 5) {
                if (!$assignFeeMaster->reminder_sent_at) {
                    $this->sendPaymentReminder($assignFeeMaster, $message);
                    $assignFeeMaster->update(['reminder_sent_at' => now()]);
                }
            } 
            elseif ($daysRemaining <= 5 && $daysRemaining > 0) {
                $lastReminderSentAt = $assignFeeMaster->reminder_sent_at 
                    ? Carbon::parse($assignFeeMaster->reminder_sent_at) 
                    : now()->subDays(2);

                if ($lastReminderSentAt->diffInDays(now()) >= 2) {
                    $this->sendPaymentReminder($assignFeeMaster, $message);
                    $assignFeeMaster->update(['reminder_sent_at' => now()]);
                }
            }
        }

        return 0;
    }

    protected function sendPaymentReminder(AssignFeeMaster $assignFeeMaster, string $message)
    {
        if (!empty($assignFeeMaster->student->phone)) {
            Sms::sendSingleMessage(str($assignFeeMaster->student->phone)->after('0')->prepend('00251')->toString(), $message);

            $this->info("Payment reminder sent successfully.");
        }
    }
}
