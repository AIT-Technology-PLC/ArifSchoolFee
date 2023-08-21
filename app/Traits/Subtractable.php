<?php

namespace App\Traits;

use App\Models\User;

trait Subtractable
{
    public function subtractedBy()
    {
        return $this->belongsTo(User::class, 'subtracted_by');
    }

    public function subtract()
    {
        $user = $this->createdBy;

        if (auth()->check()) {
            $user = authUser();
        }

        $this->subtracted_by = $user->id;

        $this->save();
    }

    public function isSubtracted()
    {
        if (is_null($this->subtracted_by)) {
            return false;
        }

        return true;
    }

    public function scopeSubtracted($query)
    {
        return $query->whereNotNull('subtracted_by');
    }

    public function scopeNotSubtracted($query)
    {
        return $query->whereNull('subtracted_by');
    }
}
