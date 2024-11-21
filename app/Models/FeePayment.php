<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;

class FeePayment extends Model
{
    use MultiTenancy, HasUserstamps;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'payment_date' => 'datetime',
        'discount_month' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function assignFeeMaster()
    {
        return $this->belongsTo(AssignFeeMaster::class, 'assign_fee_master_id');
    }

    public function feeDiscount()
    {
        return $this->belongsTo(FeeDiscount::class);
    }
}
