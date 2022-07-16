<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use Illuminate\Support\Facades\DB;

class EmployeeTransferService
{
    public function approve($employeeTransfer)
    {
        [$isExecuted, $message] = (new ApproveTransactionAction)->execute($employeeTransfer);

        if (!$isExecuted) {
            return [$isExecuted, $message];
        }

        DB::transaction(function () use ($employeeTransfer) {
            $employeeTransfer->employeeTransferDetails->each(function ($employeeTransferDetail) {
                $employeeTransferDetail->employee->user->warehouse_id = $employeeTransferDetail->warehouse_id;
                $employeeTransferDetail->employee->user->save();
            });
        });

        return [true, $message];
    }
}
