<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DebitSettlement extends Model
{
    use SoftDeletes, HasFactory;

    protected $casts = [
        'settled_at' => 'datetime',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected static function booted()
    {
        static::created(function ($debitSettlement) {
            $debit = $debitSettlement->debit;
            $debit->debit_amount_settled = $debit->debitSettlements()->sum('amount');
            $debit->last_settled_at = $debitSettlement->settled_at;

            $debit->save();
        });

        static::updated(function ($debitSettlement) {
            $debit = $debitSettlement->debit;
            $debit->debit_amount_settled = $debit->debitSettlements()->sum('amount');
            $debit->last_settled_at = $debitSettlement->settled_at;

            $debit->save();
        });

        static::deleted(function ($debitSettlement) {
            $debit = $debitSettlement->debit;
            $debit->debit_amount_settled = $debit->debitSettlements()->sum('amount');
            $debit->last_settled_at = $debit->debitSettlements()->max('settled_at');

            $debit->save();
        });
    }

    public function debit()
    {
        return $this->belongsTo(Debit::class);
    }
}
