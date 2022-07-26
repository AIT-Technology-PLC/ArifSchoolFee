<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\Rejectable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseClaim extends Model
{
    use MultiTenancy, HasFactory, Approvable, Rejectable, Branchable, SoftDeletes, HasUserstamps;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($expenseClaim) {
            if (auth()->check()) {
                $expenseClaim['code'] = nextReferenceNumber('expense_claims');
            }
        });
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function expenseClaimDetails()
    {
        return $this->hasMany(ExpenseClaimDetail::class);
    }

    public function getTotalPriceAttribute()
    {
        return $this->expenseClaimDetails()->sum('price');
    }
}
