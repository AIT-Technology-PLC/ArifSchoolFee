<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Actions\CancelTransactionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserLeaveRequest;
use App\Models\Leave;
use App\Models\LeaveCategory;
use App\Notifications\LeaveApproved;
use App\Notifications\LeaveCreated;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class LeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Leave Management');
    }

    public function approve(Leave $leaf, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $leaf);

        if ($leaf->isCancelled()) {
            return back()->with('failedMessage', 'You can not approve a leave that is cancelled.');
        }

        [$isExecuted, $message] = $action->execute($leaf);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Leave', $leaf->warehouse_id, $leaf->createdBy)->push($leaf->employee->user),
            new LeaveApproved($leaf)
        );

        return back()->with('successMessage', $message);
    }

    public function cancel(Leave $leaf, CancelTransactionAction $action)
    {
        $this->authorize('cancel', $leaf);

        if ($leaf->isApproved()) {
            return back()->with('failedMessage', 'You can not cancel a leave that is approved..');
        }

        [$isExecuted, $message] = $action->execute($leaf);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function createRequestLeave()
    {
        $leaveCategories = LeaveCategory::orderBy('name')->get(['id', 'name']);

        return view('leaves.leave-requests.create', compact('leaveCategories'));
    }

    public function storeRequestLeave(StoreUserLeaveRequest $request)
    {
        $leaveRequest = DB::transaction(function () use ($request) {
            $leaveRequest = Leave::create($request->validated() + ['employee_id' => authUser()->employee->id]);

            Notification::send(Notifiables::byNextActionPermission('Approve Leave'), new LeaveCreated($leaveRequest));

            return $leaveRequest;
        });

        return redirect()->route('leaves.show', $leaveRequest->id);
    }
}
