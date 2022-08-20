<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use Illuminate\Support\Facades\DB;

class LeaveService
{
    public function approve($leaf)
    {
        if (!$leaf->isPaidTimeOff()) {
            [$isExecuted, $message] = (new ApproveTransactionAction)->execute($leaf);

            if (!$isExecuted) {
                return [$isExecuted, $message];
            }

            return [true, $message];
        }

        if ($leaf->isPaidTimeOff() && $leaf->employee->paid_time_off_amount >= $leaf->time_off_amount) {
            DB::transaction(function () use ($leaf) {
                $leaf->employee->paid_time_off_amount -= $leaf->time_off_amount;

                $leaf->employee->save();

                [$isExecuted, $message] = (new ApproveTransactionAction)->execute($leaf);

                if (!$isExecuted) {
                    return [$isExecuted, $message];
                }

                return [true, $message];
            });
        }
    }
}