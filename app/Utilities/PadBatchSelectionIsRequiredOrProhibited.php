<?php

namespace App\Utilities;

use App\Models\Product;

class PadBatchSelectionIsRequiredOrProhibited
{
    private $shallCheckSettings;

    private $message;

    public function __construct($shallCheckSettings = true, $requestData, $productPadField)
    {
        $this->shallCheckSettings = $shallCheckSettings;

        $this->message = 'Batch no is required.';

        $this->requestData = $requestData;

        $this->productPadField = $productPadField;
    }

    public function passes($attribute, $value)
    {
        $productId = $this->requestData[str($attribute)->betweenFirst('.', '.')->toString()][$this->productPadField->id] ?? null;
        $isMerchandiseBatchSelected = !empty($value);

        if (empty($productId) || !Product::find($productId)->isBatchable()) {
            return [];
        }

        if (!$this->shallCheckSettings && !$isMerchandiseBatchSelected) {
            return ['required', 'string'];
        }

        if ($this->shallCheckSettings && !userCompany()->canSelectBatchNumberOnForms() && $isMerchandiseBatchSelected) {
            $this->message = 'Manual batch selection is disabled.';
            return ['exclude'];
        }

        if ($this->shallCheckSettings && userCompany()->canSelectBatchNumberOnForms() && !$isMerchandiseBatchSelected) {
            return ['required', 'string'];
        }

        return [];
    }
}
