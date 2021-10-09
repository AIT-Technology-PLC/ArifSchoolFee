<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\Damage;
use App\Notifications\DamageApproved;
use App\Notifications\DamageSubtracted;
use App\Services\DamageService;
use Illuminate\Support\Facades\Notification;

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
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function subtract(Damage $damage, DamageService $damageService)
    {
        $this->authorize('subtract', $damage);

        [$isExecuted, $message] = $damageService->subtract($damage);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(notifiables('Approve Damage', $damage->createdBy), new DamageSubtracted($damage));

        return back();
    }
}
