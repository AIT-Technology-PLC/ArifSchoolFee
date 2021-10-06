<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\Grn;
use App\Notifications\GrnApproved;
use App\Traits\AddInventory;

class GrnController extends Controller
{
    use AddInventory;

    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Grn Management');
    }

    public function approve(Grn $grn, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $grn);

        [$isExecuted, $message] = $action->execute($grn, GrnApproved::class, 'Add GRN');

        if (!$isExecuted) {
            return redirect()->back()->with('failedMessage', $message);
        }

        return redirect()->back()->with('successMessage', $message);
    }
}
