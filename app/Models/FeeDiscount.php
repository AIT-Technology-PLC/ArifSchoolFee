<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class FeeDiscount extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps, LogsActivity;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Fee Discount');
    }

    public function assignFeeDiscounts()
    {
        return $this->belongsToMany(FeeDiscount::class, 'assign_fee_discounts', 'student_id', 'fee_discount_id');
    }

    public function feePayments()
    {
        return $this->hasMany(FeePayment::class);
    }
}
