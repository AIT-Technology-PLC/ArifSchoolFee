<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\Damage;
use App\Notifications\DamageApproved;
use App\Traits\SubtractInventory;

class DamageController extends Controller
{
    use SubtractInventory;

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
}
