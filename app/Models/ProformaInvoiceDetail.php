<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProformaInvoiceDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function proformaInvoice()
    {
        return $this->belongsTo(ProformaInvoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getDiscountAttribute($value)
    {
        if (is_null($value)) {
            return 0;
        }

        return $value / 100;
    }
    public function getUnitPriceAfterDiscountAttribute()
    {
        return ($this->unit_price * $this->quantity) -
            (($this->unit_price * $this->quantity) * $this->discount);
    }
}
