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
        $this->authorize('create', Damage::class);

        [$isExecuted, $message, $damage] = $this->merchandiseBatchService->convertToDamage($merchandiseBatch);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return redirect()->route('damages.show', $damage->id);
    }

    public function productBatch(MerchandiseBatch $merchandiseBatch)
    {
        $this->authorize('Read Expired Inventory', Merchandise::class);

        $merchandiseBatches = MerchandiseBatch::query()
            ->whereRelation('merchandise', 'company_id', userCompany()->id)
            ->join('merchandises', 'merchandise_batches.merchandise_id', '=', 'merchandises.id')
            ->join('products', 'merchandises.product_id', '=', 'products.id')
            ->join('companies', 'merchandises.company_id', '=', 'companies.id')
            ->where('merchandise_batches.quantity', '>', 0)
            ->whereRaw('CASE
                        WHEN companies.expiry_time_type = "Days" THEN DATEDIFF(expiry_date, CURRENT_DATE) = companies.expired_in
                        WHEN companies.expiry_time_type = "Months" THEN DATEDIFF(expiry_date, CURRENT_DATE) = companies.expired_in*30
                        ELSE DATEDIFF(expiry_date, CURRENT_DATE) = companies.expired_in*365
                    END')
            ->select(['merchandise_batches.batch_no', 'merchandise_batches.quantity', 'merchandise_batches.expiry_date', 'products.name'])
            ->get();

        return view('merchandise-batches.index', compact('merchandiseBatches'));
    }
}