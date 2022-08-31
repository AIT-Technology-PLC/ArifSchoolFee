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
            return [$isExecuted, $message];
        }

        if ($leaf->time_off_amount > $leaf->employee->paid_time_off_amount) {
            return [false, 'Employee has no enough Paid Time Off.'];
        }

        return DB::transaction(function () use ($leaf) {
            $leaf->employee->paid_time_off_amount -= $leaf->time_off_amount;

            $leaf->employee->save();

            [$isExecuted, $message] = (new ApproveTransactionAction)->execute($leaf);

            return [$isExecuted, $message];
        });
    }
}
