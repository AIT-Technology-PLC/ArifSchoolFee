<?php

namespace App\Models;

use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EarningDetail extends Model
{
    use SoftDeletes, TouchParentUserstamp;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function earning()
    {
        return $this->belongsTo(Earning::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function earningCategory()
    {
        return $this->belongsTo(EarningCategory::class);
    }

    public function parentModel()
    {
        return $this->earning;
    }
}
