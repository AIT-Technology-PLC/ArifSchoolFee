<?php

namespace App\Models;

use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenderDetail extends Model
{
    use SoftDeletes, TouchParentUserstamp;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function parentModel()
    {
        return $this->tender;
    }
}
