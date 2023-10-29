<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Returnn;
use App\Notifications\ReturnAdded;
use App\Services\Models\ReturnService;
use App\Utilities\Notifiables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Notification;

class ReturnController extends Controller
{
    private $returnService;

    public function __construct(ReturnService $returnService)
    {
        $this->middleware('isFeatureAccessible:Return Management');

        $this->returnService = $returnService;
    }

    public function approve(Returnn $return)
    {
        $this->authorize('approve', $return);

        [$isExecuted, $message] = $this->returnService->approve($return);

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

        $return->load(['returnDetails.product', 'customer', 'warehouse', 'company', 'gdn.customer', 'createdBy', 'approvedBy']);

        return Pdf::loadView('returns.print', compact('return'))->stream();
    }

    public function add(Returnn $return, ReturnService $returnService)
    {
        $this->authorize('add', $return);

        [$isExecuted, $message] = $returnService->add($return, authUser());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Return', $return->returnDetails->pluck('warehouse_id'), $return->createdBy),
            new ReturnAdded($return)
        );

        return back();
    }

    public function approveAndAdd(Returnn $return, ReturnService $returnService)
    {
        $this->authorize('approve', $return);

        $this->authorize('add', $return);

        [$isExecuted, $message] = $returnService->approveAndAdd($return, authUser());

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
