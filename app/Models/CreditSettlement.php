<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreditSettlement extends Model
{
    use SoftDeletes, HasFactory;

    protected $casts = [
        'settled_at' => 'datetime',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected static function booted()
    {
        static::created(function ($creditSettlement) {
            $credit = $creditSettlement->credit;
            $credit->credit_amount_settled = $credit->creditSettlements()->sum('amount');
            $credit->last_settled_at = $creditSettlement->settled_at;

            $credit->save();
        });

        static::updated(function ($creditSettlement) {
            $credit = $creditSettlement->credit;
            $credit->credit_amount_settled = $credit->creditSettlements()->sum('amount');
            $credit->last_settled_at = $creditSettlement->settled_at;

            $credit->save();
        });

        static::deleted(function ($creditSettlement) {
            $credit = $creditSettlement->credit;
            $credit->credit_amount_settled = $credit->creditSettlements()->sum('amount');
            $credit->last_settled_at = $credit->creditSettlements()->max('settled_at');

            $credit->save();
        });
    }

    public function credit()
    {
        return $this->belongsTo(Credit::class);
    }
}
