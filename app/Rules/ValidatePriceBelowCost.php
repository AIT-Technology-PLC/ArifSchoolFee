<?php

namespace App\Rules;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidatePriceBelowCost implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!isFeatureEnabled('Inventory Valuation') || userCompany()->canSellBelowCost()) {
            return;
        }

        $productIdKey = str_replace(['.fixed_price', '.unit_price'], '.product_id', $attribute);

        $productId = request()->input($productIdKey);

        $product = Product::with('tax')->find($productId);

        if (is_null($product)) {
            $fail('Product does not exist.');
            return;
        }

        if (!userCompany()->isPriceBeforeTax()) {
            $value = $value / ($product->tax->amount + 1);
        }

        if ($value < $product->unitCost) {
            $fail('The price of product must be greater than or equal to the unit cost.');
        }
    }
}
