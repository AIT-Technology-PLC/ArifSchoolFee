<?php

namespace App;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Merchandise;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_online_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id');
    }

    public function employeesCreated()
    {
        return $this->hasMany(Employee::class, 'created_by');
    }

    public function employeesUpdated()
    {
        return $this->hasMany(Employee::class, 'updated_by');
    }

    public function warehousesCreated()
    {
        return $this->hasMany(Warehouse::class, 'created_by');
    }

    public function warehousesUpdated()
    {
        return $this->hasMany(Warehouse::class, 'updated_by');
    }

    public function suppliersCreated()
    {
        return $this->hasMany(Supplier::class, 'created_by');
    }

    public function suppliersUpdated()
    {
        return $this->hasMany(Supplier::class, 'updated_by');
    }

    public function productCategoriesCreated()
    {
        return $this->hasMany(ProductCategory::class, 'created_by');
    }

    public function productCategoriesUpdated()
    {
        return $this->hasMany(ProductCategory::class, 'updated_by');
    }

    public function productsCreated()
    {
        return $this->hasMany(Product::class, 'created_by');
    }

    public function productsUpdated()
    {
        return $this->hasMany(Product::class, 'updated_by');
    }

    public function purchasesCreated()
    {
        return $this->hasMany(Purchase::class, 'created_by');
    }

    public function purchasesUpdated()
    {
        return $this->hasMany(Purchase::class, 'updated_by');
    }

    public function merchandisesCreated()
    {
        return $this->hasMany(Merchandise::class, 'created_by');
    }

    public function merchandisesUpdated()
    {
        return $this->hasMany(Merchandise::class, 'updated_by');
    }

    public function manufacturingsCreated()
    {
        return $this->hasMany(Manufacturing::class, 'created_by');
    }

    public function manufacturingsUpdated()
    {
        return $this->hasMany(Manufacturing::class, 'updated_by');
    }

    public function rawMaterialsCreated()
    {
        return $this->hasMany(RawMaterial::class, 'created_by');
    }

    public function rawMaterialsUpdated()
    {
        return $this->hasMany(RawMaterial::class, 'updated_by');
    }

    public function billOfMaterialsCreated()
    {
        return $this->hasMany(BillOfMaterial::class, 'created_by');
    }

    public function billOfMaterialsUpdated()
    {
        return $this->hasMany(BillOfMaterial::class, 'updated_by');
    }

    public function mroItemsCreated()
    {
        return $this->hasMany(MroItem::class, 'created_by');
    }

    public function mroItemsUpdated()
    {
        return $this->hasMany(MroItem::class, 'updated_by');
    }

    public function customersCreated()
    {
        return $this->hasMany(Customer::class, 'created_by');
    }

    public function customersUpdated()
    {
        return $this->hasMany(Customer::class, 'updated_by');
    }

    public function salesCreated()
    {
        return $this->hasMany(Sale::class, 'created_by');
    }

    public function salesUpdated()
    {
        return $this->hasMany(Sale::class, 'updated_by');
    }
}
