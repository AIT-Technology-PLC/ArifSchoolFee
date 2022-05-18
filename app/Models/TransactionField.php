<?php

namespace App\Models;

use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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

    public function scopeMasterFields($query)
    {
        return $query->whereNull('line');
    }

    public function scopeDetailFields($query)
    {
        return $query->whereNotNull('line');
    }

    public function relationValue(): Attribute
    {
        return Attribute::make(
            get:function () {
                return is_numeric($this->value)
                ? DB::table(str($this->padField->padRelation->model_name)->lower()->plural())
                    ->find($this->value)
                    ->{$this->padField->padRelation->representative_column}
                : null;
            }
        );
    }
}
