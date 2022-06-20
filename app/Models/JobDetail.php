<?php

namespace App\Models;

use App\Models\BillOfMaterial;
use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobDetail extends Model
{
    use SoftDeletes, TouchParentUserstamp;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function billOfMaterial()
    {
        return $this->belongsTo(BillOfMaterial::class);
    }

    public function scopeWip($query)
    {
        return $query->where('wip','>',0);
    }

    public function scopeAvailable($query)
    {
        return $query->where('available','>',0);
    }

    public function parentModel()
    {
        return $this->job;
    }
}
