<?php

namespace App\Rules;

use App\Models\Price;
use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Arr;

class ValidatePrice implements ValidationRule
{
    private $details;

    public function __construct($details = [])
    {
        $this->details = $details;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $productIdKey = str_replace('.unit_price', '.product_id', $attribute);

        $productId = request()->input($productIdKey) ?? Arr::get($this->details, $productIdKey);

        if (Product::where('id', $productId)->doesntExist()) {
            $fail('The selected product does not exist.');
            return;
        }

        if (isFeatureEnabled('Inventory Valuation') && !userCompany()->canSellBelowCost()) {
            $unitCost = Product::find($productId)->unitCost;

            if (!userCompany()->isPriceBeforeTax()) {
                $value = $value / (Product::find($productId)->tax->amount + 1);
            }

            $this->message = 'The selected price is below the unit cost of the product.';

            return $value >= $unitCost;
        }

        $haveActivePrices = Price::active()->where('product_id', $productId)->exists();

        if (!$haveActivePrices) {
            return;
        }

        if (Price::active()->where('product_id', $productId)->where('fixed_price', $value)->doesntExist()) {
            $fail('The selected price is incorrect.');
        }
    }
}
