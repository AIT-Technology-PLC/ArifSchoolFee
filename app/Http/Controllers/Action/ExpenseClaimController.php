<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Actions\RejectTransactionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeExpenseClaimRequest;
use App\Models\Employee;
use App\Models\ExpenseClaim;
use App\Notifications\ExpenseClaimApproved;
use App\Notifications\ExpenseClaimCreated;
use App\Notifications\ExpenseClaimRejected;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

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
            return back()->with('failedMessage', 'You can not approve an expense claims that is rejected.');
        }

        [$isExecuted, $message] = $action->execute($expenseClaim);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Expense Claim', $expenseClaim->warehouse_id, $expenseClaim->createdBy),
            new ExpenseClaimApproved($expenseClaim)
        );

        return back()->with('successMessage', $message);
    }

    public function reject(ExpenseClaim $expenseClaim, RejectTransactionAction $action)
    {
        $this->authorize('reject', $expenseClaim);

        if ($expenseClaim->isapproved()) {
            return back()->with('failedMessage', 'You can not reject an expense claims that is approved.');
        }

        [$isExecuted, $message] = $action->execute($expenseClaim);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Expense Claim', $expenseClaim->warehouse_id, $expenseClaim->rejectedBy),
            new ExpenseClaimRejected($expenseClaim)
        );

        return back()->with('successMessage', $message);
    }

    public function createExpenseClaim()
    {
        return view('expense-claims.employee.create');
    }

    public function storeExpenseClaim(StoreEmployeeExpenseClaimRequest $request)
    {
        $expenseClaim = DB::transaction(function () use ($request) {
            $expenseClaim = ExpenseClaim::create($request->safe()->except('expenseClaim') + ['employee_id' => authUser()->employee->id]);

            $expenseClaim->expenseClaimDetails()->createMany($request->validated('expenseClaim'));

            Notification::send(Notifiables::byNextActionPermission('Approve Expense Claim'), new ExpenseClaimCreated($expenseClaim));

            return $expenseClaim;
        });

        return redirect()->route('expense-claims.show', $expenseClaim->id);
    }
}
