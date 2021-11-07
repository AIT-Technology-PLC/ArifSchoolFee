<?php

namespace App\Models;

use App\Traits\Branchable;
use App\Traits\Closable;
use App\Traits\Discountable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\PricingTicket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use MultiTenancy, Branchable, SoftDeletes, HasUserstamps, PricingTicket, Discountable, Closable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'purchased_on' => 'datetime',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function grns()
    {
        return $this->hasMany(Grn::class);
    }

    public function details()
    {
        return $this->purchaseDetails;
    }

    public function isImported()
    {
        return $this->type == 'Import';
    }
}
