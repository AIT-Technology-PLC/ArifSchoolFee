<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrnDetail extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function grn()
    {
        return $this->belongsTo(Grn::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
