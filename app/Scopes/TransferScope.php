<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TransferScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (!auth()->check()) {
            return;
        }

        if (auth()->user()->hasRole('System Manager')) {
            return;
        }

        $table = $model->getTable();

        $builder
            ->where(function ($query) use ($table) {
                $query->where("{$table}.transferred_from", auth()->user()->warehouse_id)
                    ->orWhere("{$table}.transferred_to", auth()->user()->warehouse_id);
            })
            ->when(
                auth()->user()->getAllowedWarehouses('transactions')->isNotEmpty(),
                function ($query) use ($table) {
                    return $query->orWhere(function ($query) use ($table) {
                        $query->whereIn("{$table}.transferred_from", auth()->user()->getAllowedWarehouses('transactions')->pluck('id'))
                            ->orWhereIn("{$table}.transferred_to", auth()->user()->getAllowedWarehouses('transactions')->pluck('id'));
                    });
                }
            );
    }
}
