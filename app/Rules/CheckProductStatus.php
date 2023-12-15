<?php

namespace App\Rules;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckProductStatus implements ValidationRule
{
    private $type;

    public function __construct($type = 'activeForSale')
    {
        $this->type = $type;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (Product::{$this->type}()->where('id', $value)->doesntExist()) {
            $fail('This product is deactivated for this type of transaction.');
        }
    }
}
