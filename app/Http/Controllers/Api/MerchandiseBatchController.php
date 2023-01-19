<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MerchandiseBatch;

class MerchandiseBatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Merchandise Inventory');
    }

    public function index()
    {
        if (!userCompany()->canSelectBatchNumberOnForms()) {
            return false;
        }

        $merchandiseBatches = MerchandiseBatch::available()
            ->whereHas('merchandise')
            ->with(['merchandise'])
            ->orderBy('expires_on')
            ->get();

        return $merchandiseBatches->map(function ($merchandiseBatch) {
            return [
                'id' => $merchandiseBatch->id,
                'name' => $merchandiseBatch->batch_no,
                'expires_on' => $merchandiseBatch->expires_on?->toFormattedDateString(),
                'product_id' => $merchandiseBatch->merchandise->product_id,
            ];
        });
    }
}
