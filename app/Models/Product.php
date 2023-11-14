<?php

namespace App\Models;

use App\Models\CostUpdateDetail;
use App\Models\InventoryValuationBalance;
use App\Models\InventoryValuationHistory;
use App\Models\ProductBundle;
use App\Models\Tax;
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
        'is_batchable' => 'integer',
        'is_active' => 'integer',
        'is_active_for_sale' => 'integer',
        'is_active_for_purchase' => 'integer',
        'is_active_for_job' => 'integer',
        'is_product_single' => 'integer',
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
        'prices',
        'jobDetails',
        'jobExtras',
        'billOfMaterials',
        'billOfMaterialDetails',
        'priceIncrementDetails',
        'jobDetailHistories',
        'inventoryHistories',
        'productReorders',
        'inventoryValuationHistories',
        'inventoryValuationBalances',
        'costUpdateDetails',
        'productBundles',
    ];

    protected static function booted()
    {
        static::deleted(function ($product) {
            $padFieldIds = PadField::whereHas('pad')->whereRelation('padRelation', 'model_name', 'Product')->pluck('id');

            $transactionFields = TransactionField::whereIn('pad_field_id', $padFieldIds)->where('value', $product->id)->get();

            foreach ($transactionFields as $transactionField) {
                TransactionField::where('transaction_id', $transactionField->transaction_id)->where('line', $transactionField->line)->delete();
            }
        });
    }

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

    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function jobDetails()
    {
        return $this->hasMany(JobDetail::class);
    }

    public function jobExtras()
    {
        return $this->hasMany(JobExtra::class);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function productReorders()
    {
        return $this->hasMany(ProductReorder::class);
    }

    public function priceIncrementDetails()
    {
        return $this->hasMany(PriceIncrementDetail::class);
    }

    public function jobDetailHistories()
    {
        return $this->hasMany(JobDetailHistory::class);
    }

    public function inventoryHistories()
    {
        return $this->hasMany(InventoryHistory::class);
    }

    public function inventoryValuationHistories()
    {
        return $this->hasMany(InventoryValuationHistory::class);
    }

    public function inventoryValuationBalances()
    {
        return $this->hasMany(InventoryValuationBalance::class);
    }

    public function costUpdateDetails()
    {
        return $this->hasMany(CostUpdateDetail::class);
    }

    public function productBundles()
    {
        return $this->hasMany(ProductBundle::class);
    }

    public function name(): Attribute
    {
        return Attribute::make(
            get: fn($value) => str()->squish($value),
            set: fn($value) => $this->attributes['name'] = str()->squish($value),
        );
    }

    public function code(): Attribute
    {
        return Attribute::make(
            get: fn($value) => str()->squish($value),
            set: fn($value) => $this->attributes['code'] = str()->squish($value),
        );
    }

    public function unitCost(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->{$this->inventory_valuation_method . '_unit_cost'},
        );
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

    public function scopeInventoryType($query)
    {
        return $query->whereIn('type', ['Raw Material', 'Finished Goods'])->where('is_product_single', 1);
    }

    public function scopeBatchable($query)
    {
        return $query->where('is_batchable', 1)->where('is_product_single', 1);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeActiveForSale($query)
    {
        return $query->where('is_active', 1)->where('is_active_for_sale', 1);
    }

    public function scopeActiveForPurchase($query)
    {
        return $query->where('is_active', 1)->where('is_active_for_purchase', 1)->where('is_product_single', 1);
    }

    public function scopeActiveForJob($query)
    {
        return $query->where('is_active', 1)->where('is_active_for_job', 1)->where('is_product_single', 1);
    }

    public function scopeRawMaterial($query)
    {
        return $query->where('type', 'Raw Material')->where('is_product_single', 1);
    }

    public function scopeFinishedGoods($query)
    {
        return $query->where('type', 'Finished Goods');
    }

    public function isProductLimited($onHandQuantity, $warehouseId)
    {
        return $this->getReorderQuantity($warehouseId) >= $onHandQuantity;
    }

    public function isTypeService()
    {
        return $this->type == 'Services';
    }

    public function isLifo()
    {
        return $this->batch_priority == 'lifo';
    }

    public function isBatchable()
    {
        return $this->is_batchable == 1 && $this->is_product_single == 1;
    }

    public function isActiveForSale()
    {
        return $this->is_active_for_sale == 1;
    }

    public function isActiveForPurchase()
    {
        return $this->is_active_for_purchase == 1 && $this->is_product_single == 1;
    }

    public function isActiveForJob()
    {
        return $this->is_active_for_job == 1 && $this->is_product_single == 1;
    }

    public function isInventoryProduct()
    {
        return $this->type == 'Finished Goods' || $this->type == 'Raw Material';
    }

    public function isNonInventoryProduct()
    {
        return $this->type == 'Non-inventory Product';
    }

    public function isProductSingle()
    {
        return $this->is_product_single == 1;
    }

    public function isBundleAndUsed()
    {
        return $this->is_product_single == 0 &&
            (
            $this->gdnDetails()->exists() ||
            $this->saleDetails()->exists() ||
            $this->reservationDetails()->exists()
        );
    }

    public function hasCost()
    {
        return $this->inventoryValuationHistories()->exists();
    }

    public function hasQuantity()
    {
        return $this->merchandises()->available()->exists();
    }

    public function getReorderQuantity($warehouseId = null)
    {
        $reorderQuantityGenerally = $this->min_on_hand;
        $reorderQuantityByWarehouse = 0;

        if (!is_null($warehouseId)) {
            $reorderQuantityByWarehouse = $this
                ->productReorders()
                ->where('warehouse_id', $warehouseId)
                ->first()?->quantity;
        }

        return $reorderQuantityByWarehouse ?: $reorderQuantityGenerally;
    }
}
