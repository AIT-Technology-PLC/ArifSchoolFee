<?php

namespace App\Traits;

use App\Models\Company;
use App\Scopes\CompanyScope;

trait MultiTenancy
{
    protected static function bootMultiTenancy()
    {
        static::addGlobalScope(new CompanyScope);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
