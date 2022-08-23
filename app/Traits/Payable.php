<?php

namespace App\Traits;

use App\Models\User;

trait Payable
{
    public function paidBy()
    {
        return $this->belongsTo(User::class, 'paid_by')->withDefault(['name' => 'N/A']);
    }

    public function pay()
    {
        $this->paid_by = authUser()->id;

        $this->save();
    }

    public function isPaid()
    {
        if (is_null($this->paid_by)) {
            return false;
        }

        return true;
    }

    public function scopePaid($query)
    {
        return $query->whereNotNull('paid_by');
    }

    public function scopeNotPaid($query)
    {
        return $query->whereNull('paid_by');
    }
}