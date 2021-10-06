<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Actions\SubtractFromInventoryAction;
use App\Http\Controllers\Controller;
use App\Models\Damage;
use App\Notifications\DamageApproved;
use App\Notifications\DamageSubtracted;

class DamageController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Damage Management');
    }

    public function approve(Damage $damage, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $damage);

        [$isExecuted, $message] = $action->execute($damage, DamageApproved::class, 'Subtract Damage');

        if (!$isExecuted) {
            return redirect()->back()->with('failedMessage', $message);
        }

        return redirect()->back()->with('successMessage', $message);
    }

    public function subtract(Damage $damage, SubtractFromInventoryAction $action)
    {
        $this->authorize('subtract', $damage);

        [$isExecuted, $message] = $action->execute($damage, DamageSubtracted::class, 'Approve Damage');

        if (!$isExecuted) {
            return redirect()->back()->with('failedMessage', $message);
        }

        return redirect()->back()->with('successMessage', $message);
    }
}
