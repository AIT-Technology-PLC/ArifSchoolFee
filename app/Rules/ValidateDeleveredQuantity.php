<?php

namespace App\Rules;

use App\Models\GdnDetail;
use App\Models\SaleDetail;
use Illuminate\Contracts\Validation\Rule;

class ValidateDeleveredQuantity implements Rule
{

    private $modelId;

    private $model;

    public function __construct($modelId, $model)
    {
        $this->modelId = $modelId;
        $this->model = $model;

    }

    public function passes($attribute, $value)
    {
        $merchandiseBatchId = request()->input(str_replace('.quantity', '.merchandise_batch_id', $attribute));
        $productId = request()->input(str_replace('.quantity', '.product_id', $attribute));
        $warehouseId = request()->input(str_replace('.quantity', '.warehouse_id', $attribute));

        $model = $this->model == 'Do' ? GdnDetail::class : SaleDetail::class;
        $modelDetail = $model::query()
            ->where(function ($query) {
                if ($this->model == 'Do') {
                    $query->where('gdn_id', $this->modelId);
                } else {
                    $query->where('sale_id', $this->modelId);
                }
            })
            ->where('product_id', $productId)
            ->when($merchandiseBatchId, function ($query, $merchandiseBatchId) {
                return $query->where('merchandise_batch_id', $merchandiseBatchId);
            })
            ->where('warehouse_id', $warehouseId)
            ->get();

        $allowedQuantity = $modelDetail->sum('quantity') - $modelDetail->sum('delivered_quantity');

        return $allowedQuantity >= $value;
    }

    public function message()
    {
        return 'You can not convert more than the available quantity!';
    }
}
