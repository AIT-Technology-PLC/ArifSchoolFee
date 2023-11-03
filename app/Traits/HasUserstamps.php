<?php

namespace App\Traits;

use App\Models\User;

trait HasUserstamps
{
    protected static function bootHasUserstamps()
    {
        static::creating(function ($model) {
            if (auth()->check() && !authUser()->isAdmin()) {
                $model->created_by = authUser()->id;
                $model->updated_by = authUser()->id;
            }
        });

        static::updating(function ($model) {
            if (auth()->check() && !authUser()->isAdmin()) {
                $model->updated_by = authUser()->id;
            }
        });
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault(['name' => 'N/A']);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by')->withDefault(['name' => 'N/A']);
    }
}
