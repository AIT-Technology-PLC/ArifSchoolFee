<?php

namespace App\Models;

use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralTenderChecklist extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function tenderChecklistType()
    {
        return $this->belongsTo(TenderChecklistType::class);
    }

    public function tenderChecklists()
    {
        return $this->hasMany(TenderChecklist::class);
    }
}
