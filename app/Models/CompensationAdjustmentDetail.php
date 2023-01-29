<?php

namespace App\Models;

use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompensationAdjustmentDetail extends Model
{
    use SoftDeletes, TouchParentUserstamp;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'options' => 'object',
        'options->overtime_period' => 'integer',
    ];

    public function compensationAdjustment()
    {
        return $this->belongsTo(CompensationAdjustment::class, 'adjustment_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function parentModel()
    {
        return $this->compensationAdjustment();
    }

    public function compensation()
    {
        return $this->belongsTo(Compensation::class);
    }
}
