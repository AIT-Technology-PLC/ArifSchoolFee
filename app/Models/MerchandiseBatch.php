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

    public function scopeAvailable($query)
    {
        return $query->where('quantity', '>', 0);
    }

    public function scopeExpired($query)
    {
        return $query->whereDate('expires_on', '<=', now());
    }

    public function isDamaged()
    {
        return !is_null($this->damageDetail);
    }

    public function isExpired()
    {
        return is_null($this->expires_on) || $this->expires_on->isPast();
    }

    public function isAvailable()
    {
        return $this->quantity > 0;
    }
}
