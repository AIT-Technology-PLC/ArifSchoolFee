<?php

namespace App\Models;

use App\Traits\Branchable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Credit extends Model
{
    use MultiTenancy, Branchable, SoftDeletes, HasUserstamps, HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
        'last_settled_at' => 'datetime',
        'due_date' => 'datetime',
    ];

    public function gdn()
    {
        return $this->belongsTo(Gdn::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function credit_settlements()
    {
        return $this->hasMany(CreditSettlement::class);
    }

    public function getSettlementPercentageAttribute()
    {
        return $this->credit_amount_settled / $this->credit_amount;
    }

    public function isSettled()
    {
        return $this->credit_amount == $this->credit_amount_settled;
    }
}
