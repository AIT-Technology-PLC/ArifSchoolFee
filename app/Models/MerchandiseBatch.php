<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MerchandiseBatch extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'expires_on' => 'date',
    ];

    public function merchandise()
    {
        return $this->belongsTo(Merchandise::class);
    }

    public function damageDetail()
    {
        return $this->hasMany(DamageDetail::class);
    }

    public function gdnDetails()
    {
        return $this->hasMany(GdnDetail::class);
    }

    public function proformaInvoiceDetails()
    {
        return $this->hasMany(ProformaInvoiceDetail::class);
    }

    public function transferDetails()
    {
        return $this->hasMany(TransferDetail::class);
    }

    public function returnDetails()
    {
        return $this->hasMany(ReturnDetail::class);
    }

    public function adjustmentDetails()
    {
        return $this->hasMany(AdjustmentDetail::class);
    }

    public function reservationDetails()
    {
        return $this->hasMany(ReservationDetail::class);
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('quantity', '>', 0);
    }

    public function scopeExpired($query)
    {
        return $query->whereDate('expires_on', '<=', today());
    }

    public function scopeNotExpired($query)
    {
        return $query->whereDate('expires_on', '>', today())->orWhereNull('expires_on');
    }

    public function scopeNearToBeExpired($query)
    {
        return $query
            ->whereDate('expires_on', '>', today())
            ->whereDate('expires_on', '<=', today()->addDays(userCompany()->expiry_in_days));
    }

    public function isExpired()
    {
        return !is_null($this->expires_on) && $this->expires_on->isPast();
    }

    public function isAvailable()
    {
        return $this->quantity > 0;
    }
}
