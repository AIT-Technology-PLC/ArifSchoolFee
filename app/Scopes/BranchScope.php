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

        if (auth()->user()->hasRole('System Manager')) {
            $builder->has('warehouse');

            return;
        }

        $table = $model->getTable();

        $builder
            ->where("{$table}.warehouse_id", auth()->user()->warehouse_id)
            ->when(
                auth()->user()->getAllowedWarehouses('transactions')->isNotEmpty(),
                function ($query) use ($table) {
                    return $query->orWhereIn("{$table}.warehouse_id", auth()->user()->getAllowedWarehouses('transactions')->pluck('id'));
                },
            );
    }
}
