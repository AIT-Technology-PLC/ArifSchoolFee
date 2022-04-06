<?php

namespace App\Models;

use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionField extends Model
{
    use SoftDeletes, TouchParentUserstamp, HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function padField()
    {
        return $this->belongsTo(PadField::class);
    }

    public function parentModel()
    {
        return $this->transaction;
    }
}
