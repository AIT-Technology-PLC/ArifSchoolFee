<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CanEditReferenceNumber implements Rule
{
    private $table;

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function passes($attribute, $value)
    {
        if ($value != nextReferenceNumber($this->table) && !userCompany()->isEditingReferenceNumberEnabled()) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'Modifying a reference number is not allowed.';
    }
}
