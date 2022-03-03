<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PadPermission extends Model
{
    use SoftDeletes;

    public function pad()
    {
        return $this->belongsTo(Pad::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
