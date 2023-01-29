<?php

namespace App\Models;

use App\Traits\PricingProduct;
use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProformaInvoiceDetail extends Model
{
    use HasFactory, SoftDeletes, PricingProduct, TouchParentUserstamp;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $appends = [
        'originalUnitPrice',
    ];

    public function proformaInvoice()
    {
        return $this->belongsTo(ProformaInvoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function merchandiseBatch()
    {
        return $this->belongsTo(MerchandiseBatch::class);
    }

    public function setProductIdAttribute($value)
    {
        if (!is_numeric($value)) {
            $this->attributes['custom_product'] = $value;

            $this->attributes['product_id'] = null;

            return;
        }

        $this->attributes['product_id'] = $value;
    }

    public function parentModel()
    {
        return $this->proformaInvoice;
    }
}
