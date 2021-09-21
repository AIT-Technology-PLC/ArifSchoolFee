<?php

namespace App\Traits;

trait Subtractable
{
    public function subtractedBy()
    {
        return $this->belongsTo(User::class, 'subtracted_by');
    }

    public function subtract()
    {
        $this->subtracted_by = auth()->id();

        $this->save();
    }

    public function isSubtracted()
    {
        if (is_null($this->subtracted_by)) {
            return false;
        }

        return true;
    }
}