<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;

class AssignFeeDiscount extends Model
{
    use MultiTenancy, HasUserstamps;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function feeDiscount()
    {
        return $this->belongsTo(FeeDiscount::class);
    }
}
