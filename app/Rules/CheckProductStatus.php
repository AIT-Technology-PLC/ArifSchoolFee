<?php

namespace App\Rules;

use App\Models\Product;
use Illuminate\Contracts\Validation\Rule;

class CheckProductStatus implements Rule
{
    private $type;

    public function __construct($type = 'activeForSale')
    {
        $this->type = $type;
    }

    public function passes($attribute, $value)
    {
        return Product::{$this->type}()->where('id', $value)->exists();
    }

    public function message()
    {
        return 'This product is deactivated for this type of transaction.';
    }
}
