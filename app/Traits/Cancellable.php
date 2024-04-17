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
        $user = $this->createdBy;

        if (auth()->check()) {
            $user = authUser();
        }

        $this->cancelled_by = $user->id;

        $this->save();
    }

    public function undoCancel()
    {
        $this->cancelled_by = null;

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
        return $query->whereNotNull("{$this->getTable()}.cancelled_by");
    }

    public function scopeNotCancelled($query)
    {
        return $query->whereNull("{$this->getTable()}.cancelled_by");
    }
}
