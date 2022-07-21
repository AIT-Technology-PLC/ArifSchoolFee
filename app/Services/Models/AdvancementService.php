<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
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
                $advancementDetail->employee->gross_salary = $advancementDetail->gross_salary;
                $advancementDetail->employee->save();
            });
        });

        return [true, $message];
    }
}
