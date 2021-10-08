<?php

namespace App\Http\Controllers\Action;

use App\Actions\AddToInventoryAction;
use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\Grn;
use App\Notifications\GrnAdded;
use App\Notifications\GrnApproved;

class GrnController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Grn Management');
    }

    public function approve(Grn $grn, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $grn);

        [$isExecuted, $message] = $action->execute($grn, GrnApproved::class, 'Add GRN');

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function add(Grn $grn, AddToInventoryAction $action)
    {
        $this->authorize('add', $grn);

        [$isExecuted, $message] = $action->execute($grn, GrnAdded::class, 'Approve GRN');

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back();
    }
}
