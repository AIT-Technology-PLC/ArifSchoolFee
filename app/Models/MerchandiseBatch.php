<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MerchandiseBatch extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function merchandise()
    {
        return $this->belongsTo(Merchandise::class);
    }

    public function damage()
    {
        return $this->belongsTo(Damage::class);
    }

    public function scopeNotConverted($query)
    {
        return $query->whereNull('damage_id');
    }

    public function gdnDetails()
    {
        return $this->hasMany(GdnDetail::class);
    }
}
