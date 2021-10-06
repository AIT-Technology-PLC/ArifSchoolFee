<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\Returnn;
use App\Notifications\ReturnApproved;
use App\Traits\AddInventory;

class ReturnController extends Controller
{
    use AddInventory;

    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Return Management');
    }

    public function approve(Returnn $return, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $return);

        [$isExecuted, $message] = $action->execute($return, ReturnApproved::class, 'Make Return');

        if (!$isExecuted) {
            return redirect()->back()->with('failedMessage', $message);
        }

        return redirect()->back()->with('successMessage', $message);
    }

    public function printed(Returnn $return)
    {
        $this->authorize('view', $return);

        $return->load(['returnDetails.product', 'customer', 'company', 'createdBy', 'approvedBy']);

        return view('returns.print', compact('return'));
    }
}
