<?php

namespace App\Rules;

use App\Models\MerchandiseBatch;
use Illuminate\Contracts\Validation\Rule;

class CheckBatchQuantity implements Rule
{
    public function passes($attribute, $value)
    {
        $merchandiseBatchId = request()->input(str_replace('.quantity', '.merchandise_batch_id', $attribute));

        return MerchandiseBatch::where('id', $merchandiseBatchId)->where('quantity', '>=', $value)->exists();
    }

    public function message()
    {
        return 'There is no sufficient amount in this batch, please check your inventory.';
    }
}
