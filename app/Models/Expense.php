<?php

namespace App\Models;

use App\Models\ExpenseDetail;
use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\PricingTicket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use MultiTenancy, SoftDeletes, Approvable, PricingTicket, Branchable, HasUserstamps;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
    ];

    public function expenseDetails()
    {
        return $this->hasMany(ExpenseDetail::class);
    }

    public function details()
    {
        return $this->expenseDetails;
    }
}