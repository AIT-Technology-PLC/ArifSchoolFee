<?php

namespace App\Rules;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BatchSelectionIsRequiredOrProhibited implements ValidationRule
{
    private $shallCheckSettings;

    private $message;

    public $implicit = true;

    public function __construct($shallCheckSettings = true)
    {
        $this->shallCheckSettings = $shallCheckSettings;

        $this->message = 'Batch no is required.';
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $productId = request()->input(str_replace(['.batch_no', '.merchandise_batch_id'], '.product_id', $attribute));
        $isMerchandiseBatchSelected = !is_null($value);

        if (is_null($productId) || !Product::find($productId)->isBatchable()) {
            return;
        }

        if (!$this->shallCheckSettings && !$isMerchandiseBatchSelected) {
            $fail($this->message);
        }

        if ($this->shallCheckSettings && !userCompany()->canSelectBatchNumberOnForms() && $isMerchandiseBatchSelected) {
            $fail('Manual batch selection is disabled.');
        }

        if ($this->shallCheckSettings && userCompany()->canSelectBatchNumberOnForms() && !$isMerchandiseBatchSelected) {
            $fail($this->message);
        }
    }
}
