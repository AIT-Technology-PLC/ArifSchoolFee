<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DebtSettlement extends Model
{
    use SoftDeletes, HasFactory;

    protected $casts = [
        'settled_at' => 'datetime',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected static function booted()
    {
        static::created(function ($debtSettlement) {
            $debt = $debtSettlement->debt;
            $debt->debt_amount_settled = $debt->debtSettlements()->sum('amount');
            $debt->last_settled_at = $debtSettlement->settled_at;

            $debt->save();
        });

        static::updated(function ($debtSettlement) {
            $debt = $debtSettlement->debt;
            $debt->debt_amount_settled = $debt->debtSettlements()->sum('amount');
            $debt->last_settled_at = $debtSettlement->settled_at;

            $debt->save();
        });

        static::deleted(function ($debtSettlement) {
            $debt = $debtSettlement->debt;
            $debt->debt_amount_settled = $debt->debtSettlements()->sum('amount');
            $debt->last_settled_at = $debt->debtSettlements()->max('settled_at');

            $debt->save();
        });
    }

    public function debt()
    {
        return $this->belongsTo(Debt::class);
    }
}
