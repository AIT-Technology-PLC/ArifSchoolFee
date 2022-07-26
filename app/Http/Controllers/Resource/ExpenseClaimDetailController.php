<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\ExpenseClaimDetail;

class ExpenseClaimDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Expense Claim');
    }

    public function destroy(ExpenseClaimDetail $expenseClaimDetail)
    {
        $this->authorize('delete', $expenseClaimDetail->expenseClaim);

        if ($expenseClaimDetail->expenseClaim->isApproved()) {
            return back()->with('failedMessage', 'You can not delete an expense claim that is approved.');
        }

        $expenseClaimDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}