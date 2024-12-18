<?php

namespace App\Traits;

use App\Models\Company;
use App\Scopes\CompanyScope;

trait MultiTenancy
{
    protected static function bootMultiTenancy()
    {
        static::addGlobalScope(new CompanyScope);

        static::creating(function ($model) {
            if (auth()->check() && !authUser()->isAdmin()  && !authUser()->isCallCenter()  && !authUser()->isBank()) {
                $model->company()->associate(userCompany());
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
