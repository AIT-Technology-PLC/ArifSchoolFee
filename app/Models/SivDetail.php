<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Siv;
use App\Models\Warehouse;
use App\Scopes\ActiveWarehouseScope;
use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SivDetail extends Model
{
    use HasFactory, SoftDeletes, TouchParentUserstamp;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function siv()
    {
        return $this->belongsTo(Siv::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class)->withoutGlobalScopes([ActiveWarehouseScope::class]);
    }

    public function parentModel()
    {
        return $this->siv;
    }
}
