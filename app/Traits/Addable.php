<?php

namespace App\Traits;

trait Addable
{
    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function add()
    {
        $this->added_by = auth()->id();

        $this->save();
    }

    public function isAdded()
    {
        if (is_null($this->added_by)) {
            return false;
        }

        return true;
    }
}