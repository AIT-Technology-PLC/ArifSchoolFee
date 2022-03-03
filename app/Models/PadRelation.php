<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PadRelation extends Model
{
    use SoftDeletes;

    protected $cascadeDeletes = [
        'padFields',
    ];

    public function padFields()
    {
        return $this->hasMany(PadField::class);
    }
}
