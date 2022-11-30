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

    public function merchandiseBatch(MerchandiseBatch $merchandiseBatch)
    {
        $this->authorize('Read Expired Inventory', MerchandiseBatch::class, 'expired');

        $merchandiseBatches = MerchandiseBatch::query()
            ->whereRelation('merchandise', 'company_id', userCompany()->id)
            ->join('merchandises', 'merchandise_batches.merchandise_id', '=', 'merchandises.id')
            ->join('products', 'merchandises.product_id', '=', 'products.id')
            ->join('companies', 'merchandises.company_id', '=', 'companies.id')
            ->where('merchandise_batches.quantity', '>', 0)
            ->whereRaw('DATEDIFF(expiry_date, CURRENT_DATE) = companies.expiry_in_days')
            ->select(['merchandise_batches.batch_no', 'merchandise_batches.quantity', 'merchandise_batches.expiry_date', 'products.name'])
            ->get();

        return view('merchandise-batches.index', compact('merchandiseBatches'));
    }
}