<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CanEditReferenceNumber implements Rule
{
    private $code;

    private $table;

    public function __construct($code, $table)
    {
        $this->code = $code;

        $this->table = $table;
    }

    public function passes($attribute, $value)
    {
        if ($this->code != nextReferenceNumber($this->table) && !userCompany()->isEditingReferenceNumberEnabled()) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'Modifying a reference number is not allowed.';
    }
}
