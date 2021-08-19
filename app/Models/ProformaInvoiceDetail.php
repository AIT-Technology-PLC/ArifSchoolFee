<?php

namespace App\Models;

use App\Traits\Discountable;
use App\Traits\PricingProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProformaInvoiceDetail extends Model
{
    use HasFactory, SoftDeletes, PricingProduct, Discountable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function proformaInvoice()
    {
        return $this->belongsTo(ProformaInvoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
