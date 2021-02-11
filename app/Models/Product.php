<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'properties' => 'array',
    ];

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function merchandises()
    {
        return $this->hasMany(Merchandise::class);
    }

    public function manufacturings()
    {
        return $this->hasMany(Manufacturing::class);
    }

    public function rawMaterials()
    {
        return $this->hasMany(RawMaterial::class);
    }

    public function billOfMaterial()
    {
        return $this->hasOne(BillOfMaterial::class);
    }

    public function mroItems()
    {
        return $this->hasMany(MroItem::class);
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
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

    public function purchaseOrderDetails()
    {
        return $this->hasMany(PurchaseOrderDetail::class);
    }

    public function grnDetails()
    {
        return $this->hasMany(GrnDetail::class);
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

    public function scopeCompanyProducts($query)
    {
        return $query->where('company_id', auth()->user()->employee->company_id);
    }

    public function scopeSaleableProducts($query)
    {
        return $query->where('type', '<>', 'Raw Material')->where('type', '<>', 'MRO Item');
    }

    public function scopeNonSaleableProducts($query)
    {
        return $query->where('type', 'Raw Material')->orWhere('type', 'MRO Item');
    }

    public function getAll()
    {
        return $this->companyProducts()->with(['productCategory', 'createdBy', 'updatedBy'])->orderBy('name')->get();
    }

    public function getProductNames()
    {
        return $this->companyProducts()->orderBy('name')->get(['id', 'name']);
    }

    public function getSaleableProducts()
    {
        return $this->companyProducts()->saleableProducts()->orderBy('name')->get();
    }

    public function getNonSaleableProducts()
    {
        return $this->companyProducts()->nonSaleableProducts()->orderBy('name')->get();
    }

    public function getProductUOM()
    {
        return $this->unit_of_measurement;
    }

    public function getAllOutOfStockMerchandises()
    {
        $allMerchandiseProducts = $this->companyProducts()->with(['merchandises'])
            ->where('type', 'Merchandise Product')->get();

        $outOfStockMerchandises = $allMerchandiseProducts->filter(function ($merchandiseProduct) {
            if ($merchandiseProduct->merchandises->isEmpty()) {
                return true;
            } else {
                return $merchandiseProduct->merchandises->where('total_on_hand', '>', 0.00)->isEmpty();
            }
        });

        return $outOfStockMerchandises;
    }

    public function getAllOutOfStockMerchandisesByWarehouse($onHandMerchandises)
    {
        return $this->companyProducts()->where('type', 'Merchandise Product')->whereNotIn('id', $onHandMerchandises->pluck('product_id'))->get();
    }

    public function getTotalOutOfStockMerchandises($outOfStockMerchandises)
    {
        $totalOutOfStockMerchandises = $outOfStockMerchandises->count();

        return $totalOutOfStockMerchandises;
    }

    public function isProductSaleable($productId = null)
    {
        if (is_null($productId)) {
            $productId = $this->id;
        }

        return $this->where('id', $productId)->saleableProducts()->exists();
    }

    public function isProductMerchandise($productId = null)
    {
        if (is_null($productId)) {
            $productId = $this->id;
        }

        return $this->where('id', $productId)->where('type', 'Merchandise Product')->exists();
    }

    public function isProductLimited($onHandQuantity)
    {
        return $this->min_on_hand >= $onHandQuantity;
    }

    public function countProductsOfCompany()
    {
        return $this->companyProducts()->count();
    }
}
