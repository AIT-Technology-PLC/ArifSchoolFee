<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\Returnn;
use App\Notifications\ReturnAdded;
use App\Notifications\ReturnApproved;
use App\Services\Models\ReturnService;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\Notification;

class ReturnController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Return Management');
    }

    public function approve(Returnn $return, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $return);

        [$isExecuted, $message] = $action->execute($return, ReturnApproved::class, 'Make Return');

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function printed(Returnn $return)
    {
        $this->authorize('view', $return);

        if (!$return->isApproved()) {
            return back()->with('failedMessage', 'This return is not approved yet.');
        }

        $return->load(['returnDetails.product', 'customer', 'company', 'createdBy', 'approvedBy']);

        return \PDF::loadView('returns.print', compact('return'))
            ->setPaper('a4', 'portrait')
            ->stream();
    }

    public function add(Returnn $return, ReturnService $returnService)
    {
        $this->authorize('add', $return);

        [$isExecuted, $message] = $returnService->add($return);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Return', $return->returnDetails->pluck('warehouse_id'), $return->createdBy),
            new ReturnAdded($return)
        );

        return back();
    }
}
