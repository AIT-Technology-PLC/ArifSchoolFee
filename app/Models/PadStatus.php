<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PadStatus extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_editable' => 'boolean',
        'is_deletable' => 'boolean',
    ];

    public function pad()
    {
        return $this->belongsTo(Pad::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function isActive()
    {
        return $this->is_active;
    }

    public function isEditable()
    {
        return $this->is_editable;
    }

    public function isDeletable()
    {
        return $this->is_deletable;
    }
}
