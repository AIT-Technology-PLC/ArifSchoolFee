<?php

namespace App\Traits;

use App\Models\User;

trait Approvable
{
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by')->withDefault(['name' => 'N/A']);
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

    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved_by');
    }

    public function scopeNotApproved($query)
    {
        return $query->whereNull('approved_by');
    }
}
