<?php

namespace App\Models;

use App\Traits\Branchable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\PricingTicket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use MultiTenancy, Branchable, SoftDeletes, HasUserstamps, PricingTicket;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'received_on' => 'datetime',
        'is_closed' => 'boolean',
    ];

    protected $attributes = [
        'is_closed' => 0,
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function purchaseOrderDetails()
    {
        return $this->hasMany(PurchaseOrderDetail::class);
    }

    public function details()
    {
        return $this->purchaseOrderDetails;
    }

    public function close()
    {
        $this->is_closed = 1;

        $this->save();
    }

    public function isClosed()
    {
        return $this->is_closed;
    }
}
