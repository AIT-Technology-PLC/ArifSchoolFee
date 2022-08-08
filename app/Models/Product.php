<?php

namespace App\Models;

use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps, CascadeSoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'properties' => 'array',
    ];

    protected $cascadeDeletes = [
        'merchandises',
        'purchaseDetails',
        'saleDetails',
        'gdnDetails',
        'transferDetails',
        'grnDetails',
        'tenderLotDetails',
        'sivDetails',
        'damageDetails',
        'proformaInvoiceDetails',
        'adjustmentDetails',
        'returnDetails',
        'reservationDetails',
        'price',
        'jobDetails',
        'jobExtras',
        'billOfMaterials',
        'billOfMaterialDetails',
    ];

    public function merchandises()
    {
        return $this->hasMany(Merchandise::class);
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function gdnDetails()
    {
        return $this->hasMany(GdnDetail::class);
    }

    public function transferDetails()
    {
        return $this->hasMany(TransferDetail::class);
    }

    public function grnDetails()
    {
        return $this->hasMany(GrnDetail::class);
    }

    public function tenderLotDetails()
    {
        return $this->hasMany(TenderLotDetail::class);
    }

    public function sivDetails()
    {
        return $this->hasMany(SivDetail::class);
    }

    public function damageDetails()
    {
        return $this->hasMany(DamageDetail::class);
    }

    public function proformaInvoiceDetails()
    {
        return $this->hasMany(ProformaInvoiceDetail::class);
    }

    public function adjustmentDetails()
    {
        return $this->hasMany(AdjustmentDetail::class);
    }

    public function returnDetails()
    {
        return $this->hasMany(ReturnDetail::class);
    }

    public function reservationDetails()
    {
        return $this->hasMany(ReservationDetail::class);
    }

    public function billOfMaterials()
    {
        return $this->hasMany(BillOfMaterial::class);
    }

    public function billOfMaterialDetails()
    {
        return $this->hasMany(BillOfMaterialDetail::class);
    }

    public function price()
    {
        return $this->hasOne(Price::class);
    }

    public function jobDetails()
    {
        return $this->hasMany(JobDetail::class);
    }

    public function jobExtras()
    {
        return $this->hasMany(JobExtra::class);
    }

    public function setPropertiesAttribute($array)
    {
        $properties = [];

        foreach ($array as $item) {
            if (is_null($item['key']) || is_null($item['value'])) {
                continue;
            }

            $properties[] = $item;
        }

        $this->attributes['properties'] = json_encode($properties);
    }

    public function name(): Attribute
    {
        return Attribute::make(
            get:fn($value) => str()->squish($value),
            set:fn($value) => $this->attributes['name'] = str()->squish($value),
        );
    }

    public function code(): Attribute
    {
        return Attribute::make(
            get:fn($value) => str()->squish($value),
            set:fn($value) => $this->attributes['code'] = str()->squish($value),
        );
    }

    public function isProductLimited($onHandQuantity)
    {
        return $this->min_on_hand >= $onHandQuantity;
    }
}
