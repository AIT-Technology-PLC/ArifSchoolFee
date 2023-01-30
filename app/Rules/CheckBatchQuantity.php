<?php

namespace App\Rules;

use App\Models\MerchandiseBatch;
use Illuminate\Contracts\Validation\Rule;

class CheckBatchQuantity implements Rule
{
    public function __construct()
    {

    }

    public function passes($attribute, $value)
    {
        $merchandiseBatchId = request()->input(str_replace('.quantity', '.merchandise_batch_id', $attribute));

        if (MerchandiseBatch::where('id', $merchandiseBatchId)->where('quantity', '<', $value)->exists()) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'There is no sufficient amount in this batch, please check your inventory.';
    }
}
