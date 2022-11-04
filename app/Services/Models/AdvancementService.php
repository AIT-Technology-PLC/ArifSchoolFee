<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use App\Models\EmployeeCompensation;
use App\Models\EmployeeCompensationHistory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AdvancementService
{
    public function approve($advancement)
    {
        [$isExecuted, $message] = (new ApproveTransactionAction)->execute($advancement);

        if (!$isExecuted) {
            return [$isExecuted, $message];
        }

        DB::transaction(function () use ($advancement) {
            $advancement->advancementDetails->each(function ($advancementDetail) {
                $advancementDetail->employee->position = $advancementDetail->job_position;
                $advancementDetail->employee->save();

                $emlpoyeeCompensation = EmployeeCompensation::where('employee_id', $advancementDetail->employee_id)->where('compensation_id', $advancementDetail->compensation_id)->first();

                if ($emlpoyeeCompensation) {
                    $changeCount = EmployeeCompensationHistory::where('employee_id', $advancementDetail->employee_id)->count();
                    $emlpoyeeCompensation->change_count = $changeCount;
                    EmployeeCompensationHistory::create($emlpoyeeCompensation->toArray());
                    $emlpoyeeCompensation->forceDelete();
                }

                EmployeeCompensation::create(Arr::only($advancementDetail->toArray(), ['employee_id', 'compensation_id', 'amount']));
            });
        });

        return [true, $message];
    }
}
