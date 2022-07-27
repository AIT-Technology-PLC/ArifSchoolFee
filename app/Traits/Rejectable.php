<?php

namespace App\Traits;

use App\Models\User;

trait Rejectable
{
    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by')->withDefault(['name' => 'N/A']);
    }

    public function reject()
    {
        $this->rejected_by = authUser()->id;

        $this->save();
    }

    public function isRejected()
    {
        if (is_null($this->rejected_by)) {
            return false;
        }

        return true;
    }

    public function scopeRejected($query)
    {
        return $query->whereNotNull('rejected_by');
    }

    public function scopeNotRejected($query)
    {
        return $query->whereNull('rejected_by');
    }
}