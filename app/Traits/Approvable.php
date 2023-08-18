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
        $user = $this->createdBy;

        if (auth()->check()) {
            $user = authUser();
        }

        $this->approved_by = $user->id;

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
