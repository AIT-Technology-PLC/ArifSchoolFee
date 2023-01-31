<?php

namespace App\Rules;

use App\Models\MerchandiseBatch;
use Illuminate\Contracts\Validation\Rule;

class CheckValidBatchNumber implements Rule
{
    public function __construct()
    {

    }

    public function passes($attribute, $value)
    {
        $productID = request()->input(str_replace('.merchandise_batch_id', '.product_id', $attribute));

        $merchandiseBatch = MerchandiseBatch::firstwhere('id', $value);

        if ($merchandiseBatch->merchandise->product_id != $productID) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'Invalid Batch Number!';
    }
}
