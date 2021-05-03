<?php

namespace App\Traits;

trait PrependCompanyId
{
    public function prependCompanyId($value)
    {
        if ($value && is_numeric($value)) {
            return userCompany()->id . '_' . $value;
        }

        return null;
    }
}
