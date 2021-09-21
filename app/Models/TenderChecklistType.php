<?php

namespace App\Models;

use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenderChecklistType extends Model
{
    use MultiTenancy, HasFactory, SoftDeletes, HasUserstamps;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'is_sensitive' => 'boolean',
    ];

    public function generalTenderChecklists()
    {
        return $this->hasMany(GeneralTenderChecklist::class);
    }

    public function isSensitive()
    {
        return $this->is_sensitive;
    }
}
