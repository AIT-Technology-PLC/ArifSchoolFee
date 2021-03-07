<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenderDetail extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
