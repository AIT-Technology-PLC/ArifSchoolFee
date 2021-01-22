<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GdnDetails extends Model
{
    public function gdn()
    {
        return $this->belongsTo(Gdn::class);
    }
}
