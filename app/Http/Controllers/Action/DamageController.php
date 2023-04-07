<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\Damage;
use App\Notifications\DamageApproved;
use App\Notifications\DamageSubtracted;
use App\Services\Models\DamageService;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\Notification;

class DamageController extends Controller
{
    private $damageService;

    public function __construct(DamageService $damageService)
    {
        $this->middleware('isFeatureAccessible:Damage Management');

        $this->damageService = $damageService;
    }

    public function approve(Damage $damage, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $damage);

        [$isExecuted, $message] = $action->execute($damage, DamageApproved::class, 'Subtract Damage');

        if (! $isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function subtract(Damage $damage)
    {
        $this->authorize('subtract', $damage);

        [$isExecuted, $message] = $this->damageService->subtract($damage, authUser());

        if (! $isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Damage', $damage->damageDetails->pluck('warehouse_id'), $damage->createdBy),
            new DamageSubtracted($damage)
        );

        return back();
    }

    public function approveAndSubtract(Damage $damage)
    {
        $this->authorize('approve', $damage);

        $this->authorize('subtract', $damage);

        [$isExecuted, $message] = $this->damageService->approveAndSubtract($damage, authUser());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Damage', $damage->damageDetails->pluck('warehouse_id'), $damage->createdBy),
            new DamageSubtracted($damage)
        );

        return back();
    }
}