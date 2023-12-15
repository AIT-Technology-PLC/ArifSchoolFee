<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CanEditReferenceNumber implements ValidationRule
{
    private $table;

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value != nextReferenceNumber($this->table) && !userCompany()->isEditingReferenceNumberEnabled()) {
            $fail('Modifying a reference number is not allowed.');
        }
    }
}
