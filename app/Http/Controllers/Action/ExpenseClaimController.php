<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Actions\RejectTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\ExpenseClaim;

class ExpenseClaimController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Expense Claim');
    }

    public function approve(ExpenseClaim $expenseClaim, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $expenseClaim);

        [$isExecuted, $message] = $action->execute($expenseClaim);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function reject(ExpenseClaim $expenseClaim, RejectTransactionAction $action)
    {
        $this->authorize('reject', $expenseClaim);

        [$isExecuted, $message] = $action->execute($expenseClaim);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }
}