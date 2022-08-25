<?php

namespace App\Http\Controllers\Action;

use App\Actions\CancelTransactionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeLeaveRequest;
use App\Models\Leave;
use App\Models\LeaveCategory;
use App\Notifications\LeaveApproved;
use App\Notifications\LeaveCreated;
use App\Services\Models\LeaveService;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class LeaveController extends Controller
{
    private $leaveService;

    public function __construct(LeaveService $leaveService)
    {
        $this->middleware('isFeatureAccessible:Leave Management');

        $this->leaveService = $leaveService;
    }

    public function approve(Leave $leaf)
    {
        $this->authorize('approve', $leaf);

        if (!authUser()->hasWarehousePermission('hr', $leaf->employee->user->warehouse_id)) {
            return back()->with('failedMessage', 'You do not have permission to approve this leave request.');
        }

        if ($leaf->isCancelled()) {
            return back()->with('failedMessage', 'You can not approve a leave that is cancelled.');
        }

        [$isExecuted, $message] = $this->leaveService->approve($leaf);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Leave', $leaf->warehouse_id, $leaf->createdBy)->push($leaf->employee->user)->unique(),
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

    public function createLeaveRequest()
    {
        $leaveCategories = LeaveCategory::orderBy('name')->get(['id', 'name']);

        return view('leaves.request.create', compact('leaveCategories'));
    }

    public function storeLeaveRequest(StoreEmployeeLeaveRequest $request)
    {
        $leave = DB::transaction(function () use ($request) {
            $leave = Leave::create($request->validated() + ['employee_id' => authUser()->employee->id]);

            Notification::send(Notifiables::byNextActionPermission('Approve Leave'), new LeaveCreated($leave));

            return $leave;
        });

        return redirect()->route('leaves.show', $leave->id);
    }
}
