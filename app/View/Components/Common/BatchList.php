<?php

namespace App\View\Components\Common;

use App\Models\MerchandiseBatch;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class BatchList extends Component
{
    public $batches;

    public $selectedId;

    public $id;

    public $name;

    public $value;

    public $productId;

    public $warehouseId;

    public function __construct(
        $selectedId = null,
        $id = 'merchandise_batch_id',
        $name = 'merchandise_batch_id',
        $value = 'id',
        $productId = null,
        $warehouseId = null
    ) {
        $this->productId = $productId;

        $this->warehouseId = $warehouseId;

        $this->batches = Cache::store('array')->rememberForever(authUser()->id . '_' . 'batchLists', function () {
            return MerchandiseBatch::available()
                ->whereHas('merchandise')
                ->when(!is_null($this->productId), fn($q) => $q->whereRelation('merchandise', 'product_id', $this->productId))
                ->when(!is_null($this->warehouseId), fn($q) => $q->whereRelation('merchandise', 'warehouse_id', $this->warehouseId))
                ->orderBy('expires_on')
                ->get();
        });

        $this->selectedId = $selectedId;

        $this->id = $id;

        $this->name = $name;

        $this->value = $value;
    }

    public function render()
    {
        return view('components.common.batch-list');
    }
}
