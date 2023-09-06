<?php

namespace App\View\Composers;

use App\Models\MerchandiseBatch;
use Illuminate\View\View;

class MerchandiseBatchComposer
{
    protected $merchandiseBatches;

    public function __construct()
    {
        $this->merchandiseBatches = MerchandiseBatch::query()
            ->notExpired()
            ->whereHas('merchandise', fn($q) => $q->whereRelation('product', 'is_batchable', 1))
            ->join('merchandises', 'merchandise_batches.merchandise_id', '=', 'merchandises.id')
            ->select([
                'merchandise_batches.id AS id',
                'merchandise_batches.batch_no AS name',
                'merchandise_batches.quantity AS quantity',
                'merchandise_batches.expires_on',
                'merchandises.product_id AS product_id',
                'merchandises.warehouse_id AS warehouse_id',
            ])
            ->orderBy('expires_on')
            ->get();
    }

    public function compose(View $view)
    {
        $view->with('merchandiseBatches', $this->merchandiseBatches);
    }
}
