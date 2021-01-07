<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SivDetails extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function siv()
    {
        return $this->belongsTo(Siv::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
