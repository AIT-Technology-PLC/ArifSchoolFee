<?php

namespace App\Scopes;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Cache;

class TransferScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (! auth()->check() || authUser()->isAdmin()) {
            return;
        }

        $table = $model->getTable();

        $builder
            ->where(function ($query) use ($table) {
                $query
                    ->whereIn("{$table}.transferred_from", $this->activeAndAllowedWarehouses())
                    ->orWhereIn("{$table}.transferred_to", $this->activeAndAllowedWarehouses());
            })
            ->whereNotIn("{$table}.transferred_from", $this->inactiveWarehouses())
            ->whereNotIn("{$table}.transferred_to", $this->inactiveWarehouses());
    }

    private function inactiveWarehouses()
    {
        $cacheKey = authUser()->id.'_'.'inactiveWarehouses';

        return Cache::store('array')
            ->rememberForever($cacheKey, function () {
                return Warehouse::inactive()->pluck('id');
            });
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
