<?php

namespace App\Models;

use App\Models\Compensation;
use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdvancementDetail extends Model
{
    use SoftDeletes, TouchParentUserstamp;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function parentModel()
    {
        return $this->advancement;
    }

    public function advancement()
    {
        return $this->belongsTo(Advancement::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function compensation()
    {
        return $this->belongsTo(Compensation::class);
    }
}