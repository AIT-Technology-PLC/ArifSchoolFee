<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GdnDetail extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function gdn()
    {
        return $this->belongsTo(Gdn::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
