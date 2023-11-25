<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BranchScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (! auth()->check() || authUser()->isAdmin()) {
            return;
        }

        $table = $model->getTable();

        $builder->whereIn("{$table}.warehouse_id", $this->activeAndAllowedWarehouses());
    }

    private function activeAndAllowedWarehouses()
    {
        $activeAndAllowedWarehouses = authUser()->warehouse->isActive() ? collect([authUser()->warehouse_id]) : collect();

        if (authUser()->getAllowedWarehouses('transactions')->isNotEmpty()) {
            $activeAndAllowedWarehouses
                ->push(...authUser()->getAllowedWarehouses('transactions')->pluck('id')->toArray());
        }

        return $activeAndAllowedWarehouses->unique();
    }
}
