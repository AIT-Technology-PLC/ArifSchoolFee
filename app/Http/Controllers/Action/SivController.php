<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\Siv;
use App\Notifications\SivApproved;

class SivController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Siv Management');
    }

    public function approve(Siv $siv, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $siv);

        if (!auth()->user()->hasWarehousePermission('siv',
            $siv->sivDetails->pluck('warehouse_id')->toArray())) {
            return back()->with('failedMessage', 'You do not have permission to approve in one or more of the warehouses.');
        }

        [$isExecuted, $message] = $action->execute($siv, SivApproved::class, 'Approve SIV');

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back();
    }

    public function printed(Siv $siv)
    {
        $this->authorize('view', $siv);

        if (!$siv->isApproved()) {
            return back()->with('failedMessage', 'This Store Issue Voucher is not approve yet.');
        }

        $siv->load(['sivDetails.product', 'sivDetails.warehouse', 'company', 'createdBy', 'approvedBy']);

        return \PDF::loadView('sivs.print', compact('siv'))
            ->setPaper('a4', 'portrait')
            ->stream();
    }
}
