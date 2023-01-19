<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Damage;
use App\Models\MerchandiseBatch;
use App\Services\Models\MerchandiseBatchService;

class MerchandiseBatchController extends Controller
{
    private $merchandiseBatchService;

    public function __construct(MerchandiseBatchService $merchandiseBatchService)
    {
        $this->middleware('isFeatureAccessible:Merchandise Inventory');

        $this->merchandiseBatchService = $merchandiseBatchService;
    }

    public function convertToDamage(MerchandiseBatch $merchandiseBatch)
    {
        $this->authorize('expired', $merchandiseBatch->merchandise);

        $this->authorize('create', Damage::class);

        [$isExecuted, $message, $damage] = $this->merchandiseBatchService->convertToDamage($merchandiseBatch);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return redirect()->route('damages.show', $damage->id);
    }
}
