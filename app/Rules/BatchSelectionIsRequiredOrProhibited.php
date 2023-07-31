<?php

namespace App\Rules;

use App\Models\Product;
use Illuminate\Contracts\Validation\ImplicitRule;

class BatchSelectionIsRequiredOrProhibited implements ImplicitRule
{
    private $shallCheckSettings;

    private $message;

    public function __construct($shallCheckSettings = true)
    {
        $this->shallCheckSettings = $shallCheckSettings;

        $this->message = 'Batch no is required.';
    }

    public function passes($attribute, $value)
    {
        $productId = request()->input(str_replace(['.batch_no', '.merchandise_batch_id'], '.product_id', $attribute));
        $isMerchandiseBatchSelected = !is_null($value);

        if (is_null($productId) || !Product::find($productId)->isBatchable()) {
            return true;
        }

        if (!$this->shallCheckSettings && !$isMerchandiseBatchSelected) {
            return false;
        }

        if ($this->shallCheckSettings && !userCompany()->canSelectBatchNumberOnForms() && $isMerchandiseBatchSelected) {
            $this->message = 'Manual batch selection is disabled.';
            return false;
        }

        if ($this->shallCheckSettings && userCompany()->canSelectBatchNumberOnForms() && !$isMerchandiseBatchSelected) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->message;
    }
}
