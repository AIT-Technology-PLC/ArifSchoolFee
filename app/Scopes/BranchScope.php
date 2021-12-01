<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BranchScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (!auth()->check()) {
            return;
        }

        $table = $model->getTable();

        $builder->whereIn("{$table}.warehouse_id", $this->activeAndAllowedWarehouses());
    }

    private function activeAndAllowedWarehouses()
    {
        $activeAndAllowedWarehouses = auth()->user()->warehouse->isActive() ? collect([auth()->user()->warehouse_id]) : collect();

        if (auth()->user()->getAllowedWarehouses('transactions')->isNotEmpty()) {
            $activeAndAllowedWarehouses
                ->push(...auth()->user()->getAllowedWarehouses('transactions')->pluck('id')->toArray());
        }

        return $activeAndAllowedWarehouses->unique();
    }
}
