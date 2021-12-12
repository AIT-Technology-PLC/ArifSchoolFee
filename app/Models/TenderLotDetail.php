<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenderLotDetail extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function tenderLot()
    {
        return $this->belongsTo(TenderLot::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
