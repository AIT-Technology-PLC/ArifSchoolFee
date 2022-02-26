<?php

namespace App\Traits;

use App\Models\User;

trait HasUserstamps
{
    protected static function bootHasUserstamps()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
                $model->updated_by = auth()->id();
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->id();
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
