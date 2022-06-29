<?php

namespace App\Traits;

trait Subtractable
{
    public function subtractedBy()
    {
        return $this->belongsTo(User::class, 'subtracted_by')->withDefault(['name' => 'N/A']);
    }

    public function subtract()
    {
        $this->subtracted_by = authUser()->id;

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
