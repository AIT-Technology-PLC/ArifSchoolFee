<?php

namespace App\Traits;

use App\Models\User;

trait Cancellable
{
    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function cancel()
    {
        $this->cancelled_by = authUser()->id;

        $this->save();
    }

    public function isCancelled()
    {
        if (is_null($this->cancelled_by)) {
            return false;
        }

        return true;
    }

    public function scopeCancelled($query)
    {
        return $query->whereNotNull('cancelled_by');
    }

    public function scopeNotCancelled($query)
    {
        return $query->whereNull('cancelled_by');
    }
}