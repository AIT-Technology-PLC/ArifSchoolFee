<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePriceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'price.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'price.*.fixed_price' => ['required', 'numeric', 'gt:0', 'max:99999999999999999999.99', function ($attribute, $value, $fail) {
                if (isFeatureEnabled('Inventory Valuation') && !userCompany()->canSellBelowCost()) {
                    $product = Product::find($this->input(str_replace('.fixed_price', '.product_id', $attribute)));

                    if (!userCompany()->isPriceBeforeTax()) {
                        $value = $value / ($product->tax->amount + 1);
                    }

                    if ($value < $product->unitCost) {
                        $fail("The price of product must be greater than or equal to the unit cost.");
                    }
                }
            },
            ],
            'price.*.name' => ['nullable', 'string'],
            'price.*.is_active' => ['required', 'boolean'],
        ];
    }
}
