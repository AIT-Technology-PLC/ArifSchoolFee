<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        'assign_fee_master_id',
        'session_id',
        'status',
        'payment_method',
    ];

    public function assignFeeMaster()
    {
        return $this->belongsTo(AssignFeeMaster::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeArifPay($query)
    {
        return $query->where('payment_method', 'Arifpay');
    }
}
