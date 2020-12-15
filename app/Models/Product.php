<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['product_category_id', 'company_id', 'supplier_id', 'created_by', 'updated_by', 'name', 'type', 'selling_price', 'purchase_price', 'unit_of_measurement', 'min_on_hand', 'properties', 'description'];

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
        return $this->companyProducts()->with(['productCategory', 'createdBy', 'updatedBy'])->get();
    }

    public function getProductNames()
    {
        return $this->companyProducts()->get(['id', 'name']);
    }

    public function getSaleableProducts()
    {
        return $this->companyProducts()->saleableProducts()->get();
    }

    public function getNonSaleableProducts()
    {
        return $this->companyProducts()->nonSaleableProducts()->get();
    }

    public function isProductSaleable($productId)
    {
        return $this->where('id', $productId)->saleableProducts()->exists();
    }

    public function countProductsOfCompany()
    {
        return $this->companyProducts()->count();
    }

    public function getProductUOM()
    {
        return $this->unit_of_measurement;
    }

    public function getAllOutOfStockMerchandises()
    {
        $allMerchandiseProducts = $this->companyProducts()->with(['merchandises', 'productCategory'])
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

    public function getTotalOutOfStockMerchandises($outOfStockMerchandises)
    {
        $totalOutOfStockMerchandises = $outOfStockMerchandises->count();

        return $totalOutOfStockMerchandises;
    }
}
