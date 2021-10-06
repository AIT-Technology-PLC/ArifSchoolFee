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

        [$isExecuted, $message] = $action->execute($siv, SivApproved::class, 'Approve SIV');

        if (!$isExecuted) {
            return redirect()->back()->with('failedMessage', $message);
        }

        return redirect()->back();
    }

    public function printed(Siv $siv)
    {
        $this->authorize('view', $siv);

        $siv->load(['sivDetails.product', 'sivDetails.warehouse', 'company', 'createdBy', 'approvedBy']);

        return \PDF::loadView('sivs.print', compact('siv'))
            ->setPaper('a4', 'portrait')
            ->stream();
    }
}
