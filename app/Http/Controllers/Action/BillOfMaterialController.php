<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\BillOfMaterial;

class BillOfMaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Bill Of Material Management');

    }

    public function approve(BillOfMaterial $billOfMaterial, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $billOfMaterial);

        [$isExecuted, $message] = $action->execute($billOfMaterial);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }
}