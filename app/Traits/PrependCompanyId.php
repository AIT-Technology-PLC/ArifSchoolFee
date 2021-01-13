<?php

namespace App\Traits;

trait PrependCompanyId
{
    public function prependCompanyId($value)
    {
        return auth()->user()->employee->company->id . '_' . $value;
    }
}
