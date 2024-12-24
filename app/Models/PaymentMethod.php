<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    public function paymentGateways()
    {
        return $this->hasMany(PaymentGateway::class);
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', 1);
    }

    public function scopeDisabled($query)
    {
        return $query->where('enabled', 0);
    }

    public function scopeOnlineMethod($query)
    {
        return $query->where('type', 'online');
    }

    public function scopeOfflineMethod($query)
    {
        return $query->where('type', 'offline');
    }
}
