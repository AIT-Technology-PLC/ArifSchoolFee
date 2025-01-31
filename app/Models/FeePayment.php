<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;

class FeePayment extends Model
{
    use MultiTenancy, HasUserstamps;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        'company_id',
        'student_id',
        'student_history_id',
        'assign_fee_master_id',
        'payment_mode',
        'fee_discount_id',
        'payment_date',
        'amount',
        'fine_amount',
        'discount_amount',
        'commission_amount',
        'discount_month',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'discount_month' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function studentHistory()
    {
        return $this->belongsTo(StudentHistory::class);
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
