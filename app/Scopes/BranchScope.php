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
            return;
        }

        $builder->where(
            "{$model->getTable()}.warehouse_id",
            auth()->user()->warehouse_id
        );
    }
}
