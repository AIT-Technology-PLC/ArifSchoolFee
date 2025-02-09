<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class AssignFeeDiscount extends Model
{
    use MultiTenancy, HasUserstamps, LogsActivity;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Assign Fee Discount');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function feeDiscount()
    {
        return $this->belongsTo(FeeDiscount::class);
    }
}
