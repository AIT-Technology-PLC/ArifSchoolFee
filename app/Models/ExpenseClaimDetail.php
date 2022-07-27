<?php

namespace App\Models;

use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseClaimDetail extends Model
{
    use SoftDeletes, TouchParentUserstamp;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function expenseClaim()
    {
        return $this->belongsTo(ExpenseClaim::class);
    }

    public function parentModel()
    {
        return $this->expanseClaim;
    }
}