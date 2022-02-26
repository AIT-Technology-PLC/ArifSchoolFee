<?php

namespace App\Models;

use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralTenderChecklist extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps, CascadeSoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $cascadeDeletes = ['tenderChecklists'];

    public function tenderChecklistType()
    {
        return $this->belongsTo(TenderChecklistType::class);
    }

    public function tenderChecklists()
    {
        return $this->hasMany(TenderChecklist::class);
    }
}
