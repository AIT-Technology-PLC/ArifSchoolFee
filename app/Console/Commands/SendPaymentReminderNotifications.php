<?php

namespace App\Console\Commands;

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
            
            if ($assignFeeMaster->feePayments->count() > 0) {
                continue;
            }

            $message = "Dear Parent,\n\n" .
                       "We hope you are well. Below are the payment details for your child, " . 
                       $assignFeeMaster->student->first_name . ", for the " . 
                       $assignFeeMaster->feeMaster->feeType->name . " fee:\n\n" . 
                       "Step #1: Please send the payment to:\n" .
                       "Amount: " . $assignFeeMaster->feeMaster->amount . "\n" .
                       "Telebirr\n" .
                       "Mikiyas Leul\n" .
                       "0933624757\n\n" .
                       "Step #2: Confirm your payment following this link: " .
                       "aitschoolpayment.com/i/" . $assignFeeMaster->invoice_number . "\n\n" .
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
