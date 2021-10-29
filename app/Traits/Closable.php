<?php

namespace App\Traits;

trait Closable
{
    public function scopeClosed($query)
    {
        return $query->where('is_closed', 1);
    }

    public function close()
    {
        $this->is_closed = 1;

        $this->save();
    }

    public function isClosed()
    {
        return $this->is_closed;
    }
}
