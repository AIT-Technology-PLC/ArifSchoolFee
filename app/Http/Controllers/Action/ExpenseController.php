<?php

namespace App\Http\Controllers\Action;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Utilities\Notifiables;
use App\Http\Controllers\Controller;
use App\Notifications\ExpenseApproved;
use App\Actions\ApproveTransactionAction;
use Illuminate\Support\Facades\Notification;

class ExpenseController extends Controller
{
        public function __construct()
    {
        $this->middleware('isFeatureAccessible:Expense Management');
    }

    public function approve(Expense $expense, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $expense);

        [$isExecuted, $message] = $action->execute($expense);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Expense', $expense->warehouse_id, $expense->createdBy),
            new ExpenseApproved($expense)
        );

        return back()->with('successMessage', $message);
    }

}