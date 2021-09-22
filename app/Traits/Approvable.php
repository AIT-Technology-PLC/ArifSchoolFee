<?php

namespace App\Traits;

use App\Models\User;

trait Approvable
{
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function approve()
    {
        $this->approved_by = auth()->id();

        $this->save();
    }

    public function isApproved()
    {
        if (is_null($this->approved_by)) {
            return false;
        }

        return true;
    }
}
