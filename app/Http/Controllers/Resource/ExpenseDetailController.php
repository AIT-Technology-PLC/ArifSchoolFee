<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\ExpenseDetail;

class ExpenseDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Expense Management');
    }

    public function destroy(ExpenseDetail $expenseDetail)
    {
        $this->authorize('delete', $expenseDetail->expense);

        if ($expenseDetail->expense->isApproved()) {
            return back()->with('failedMessage', 'You can not delete an expense that is approved.');
        }

        $expenseDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}