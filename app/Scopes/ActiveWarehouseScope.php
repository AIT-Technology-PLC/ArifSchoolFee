<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ActiveWarehouseScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (! auth()->check() || authUser()->isAdmin()) {
            return;
        }

        if (request()->routeIs('warehouses.*') && ! request()->routeIs('warehouses.merchandises')) {
            return;
        }

        $builder->where("{$model->getTable()}.is_active", 1);
    }
}
