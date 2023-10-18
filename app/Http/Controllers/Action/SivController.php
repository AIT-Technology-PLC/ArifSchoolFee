<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\Siv;
use App\Notifications\SivApproved;
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

    public function approve(Siv $siv, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $siv);

        if (!authUser()->hasWarehousePermission('siv',
            $siv->sivDetails->pluck('warehouse_id')->toArray())) {
            return back()->with('failedMessage', 'You do not have permission to approve in one or more of the warehouses.');
        }

        [$isExecuted, $message] = $action->execute($siv);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read SIV', $siv->sivDetails->pluck('warehouse_id'), $siv->createdBy),
            new SivApproved($siv)
        );

        return back();
    }

    public function printed(Siv $siv)
    {
        $this->authorize('view', $siv);

        if (!$siv->isApproved()) {
            return back()->with('failedMessage', 'This Store Issue Voucher is not approve yet.');
        }

        $siv->load(['sivDetails.product', 'sivDetails.warehouse', 'warehouse', 'company', 'createdBy', 'approvedBy']);

        $havingCode = $siv->sivDetails()->with('product')->get()->pluck('product')->pluck('code')->filter()->isNotEmpty();

        return Pdf::loadView('sivs.print', compact('siv', 'havingCode'))->stream();
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
