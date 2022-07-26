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

        if ($expenseClaim->isRejected()) {
            return back()->with('failedMessage', 'You can not approve an expense claim that is rejected.');
        }

        [$isExecuted, $message] = $action->execute($expenseClaim);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function reject(ExpenseClaim $expenseClaim, RejectTransactionAction $action)
    {
        $this->authorize('reject', $expenseClaim);

        if ($expenseClaim->isapproved()) {
            return back()->with('failedMessage', 'You can not reject an expense claim that is approved.');
        }

        [$isExecuted, $message] = $action->execute($expenseClaim);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }
}