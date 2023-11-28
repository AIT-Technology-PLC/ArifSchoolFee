<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Siv;
use App\Notifications\SivSubtracted;
use App\Services\Models\SivService;
use App\Utilities\Notifiables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Notification;

class SivController extends Controller
{
    private $sivService;

    public function __construct(SivService $sivService)
    {
        $this->middleware('isFeatureAccessible:Siv Management');

        $this->sivService = $sivService;
    }

    public function approve(Siv $siv)
    {
        $this->authorize('approve', $siv);

        [$isExecuted, $message] = $this->sivService->approve($siv);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function printed(Siv $siv)
    {
        $this->authorize('view', $siv);

        if (!$siv->isApproved()) {
            return back()->with('failedMessage', 'This Store Issue Voucher is not approve yet.');
        }

        $siv->load(['sivDetails.product', 'sivDetails.warehouse', 'warehouse', 'company', 'createdBy', 'approvedBy', 'customFieldValues.customField']);

        return Pdf::loadView('sivs.print', compact('siv'))->stream();
    }

    public function subtract(Siv $siv)
    {
        $this->authorize('subtract', $siv);

        [$isExecuted, $message] = $this->sivService->subtract($siv, authUser());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read SIV', $siv->sivDetails->pluck('warehouse_id'), $siv->createdBy),
            new SivSubtracted($siv)
        );

        return back();
    }

    public function approveAndSubtract(Siv $siv)
    {
        $this->authorize('approve', $siv);

        $this->authorize('subtract', $siv);

        [$isExecuted, $message] = $this->sivService->approveAndSubtract($siv, authUser());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read SIV', $siv->sivDetails->pluck('warehouse_id'), $siv->createdBy),
            new SivSubtracted($siv)
        );

        return back();
    }
}
