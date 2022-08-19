<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use App\Models\Employee;
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

        $employee = Employee::where('id', $leaf->employee_id)->first();

        if ($leaf->isPaidTimeOff() && $employee->paid_time_off_amount >= $leaf->time_off_amount) {
            DB::transaction(function () use ($leaf, $employee) {
                $employee->paid_time_off_amount -= $leaf->time_off_amount;

                $employee->save();

                [$isExecuted, $message] = (new ApproveTransactionAction)->execute($leaf);

                if (!$isExecuted) {
                    return [$isExecuted, $message];
                }

                return [true, $message];
            });
        }
    }
}