<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MultiTenancy;

class PaymentGateway extends Model
{
    use HasFactory, MultiTenancy;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
