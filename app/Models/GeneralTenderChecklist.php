<?php

namespace App\Models;

use App\Traits\MultiTenancy;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralTenderChecklist extends Model
{
    use MultiTenancy, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function tenderChecklistType()
    {
        return $this->belongsTo(TenderChecklistType::class);
    }

    public function tenderChecklists()
    {
        return $this->hasMany(TenderChecklist::class);
    }

    public function getAll()
    {
        return $this->orderBy('item', 'asc')->get();
    }
}
