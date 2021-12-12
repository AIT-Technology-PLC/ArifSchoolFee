<?php

namespace App\Models;

use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenderLot extends Model
{
    use HasFactory, SoftDeletes, TouchParentUserstamp;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    public function tenderLotDetails()
    {
        return $this->hasMany(TenderLotDetail::class);
    }

    public function parentModel()
    {
        return $this->tender;
    }
}
