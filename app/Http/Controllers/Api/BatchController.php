<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MerchandiseBatch;

class BatchController extends Controller
{
    public function index()
    {
        if (!userCompany()->canSelectBatchNumberOnForms()) {
            return false;
        }

        $merchandiseBatches = MerchandiseBatch::with(['merchandise'])->where('quantity', '>', 0)->orderBy('expiry_date')->get();

        return $merchandiseBatches->map(function ($merchandiseBatch) {
            return [
                'id' => $merchandiseBatch->id,
                'name' => $merchandiseBatch->batch_no,
                'expiry_date' => $merchandiseBatch->expiry_date->toFormattedDateString(),

                'product_id' => $merchandiseBatch->merchandise->product_id,
            ];
        });
    }
}
