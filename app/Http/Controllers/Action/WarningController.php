<?php

namespace App\Http\Controllers\Action;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\ApproveTransactionAction;
use App\Models\Warning;
use App\Notifications\WarningApproved;

class WarningController extends Controller
{    
    public function approve(Warning $warning, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $warning);

        [$isExecuted, $message] = $action->execute($warning, WarningApproved::class);

        if (! $isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }
}
