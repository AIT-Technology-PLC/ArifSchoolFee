<?php

namespace App\Models;

use App\Traits\Branchable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use MultiTenancy, Branchable, SoftDeletes, HasUserstamps;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
    ];

    public function pad()
    {
        return $this->belongsTo(Pad::class);
    }

    public function transactionFields()
    {
        return $this->hasMany(TransactionField::class);
    }

    public function isAdded()
    {
        return $this->transactionFields()->where('key', 'added_by')->exists();
    }

    public function isSubtracted()
    {
        return $this->transactionFields()->where('key', 'subtracted_by')->exists();
    }

    public function isApproved()
    {
        return $this->transactionFields()->where('key', 'approved_by')->exists();
    }

    public function isCancelled()
    {
        return $this->transactionFields()->where('key', 'cancelled_by')->exists();
    }

    public function isClosed()
    {
        return $this->transactionFields()->where('key', 'closed_by')->exists();
    }

    public function subtotalPrice(): Attribute
    {
        $unitPricePadField = PadField::firstWhere('label', 'Unit Price');
        $quantityPadField = PadField::firstWhere('label', 'Quantity');

        $unitPrice = $this->transactionFields()->where('pad_field_id', $unitPricePadField->id)->sum('value');
        $quantity = $this->transactionFields()->where('pad_field_id', $quantityPadField->id)->sum('value');

        return Attribute::make(
            get:fn() => number_format(
                $unitPrice * $quantity,
                2,
                thousands_separator:''
            )
        );
    }

    public function vat(): Attribute
    {
        return Attribute::make(
            get:fn() => number_format(
                $this->subtotalPrice * 0.15,
                2,
                thousands_separator:''
            )
        );
    }

    public function grandTotalPrice(): Attribute
    {
        return Attribute::make(
            get:fn() => number_format(
                $this->subtotalPrice + $this->vat,
                2,
                thousands_separator:''
            )
        );
    }

    public function grandTotalPriceAfterDiscount(): Attribute
    {
        $discountPadField = PadField::firstWhere([
            ['label', 'Discount'],
            ['is_master_field', 0],
        ]);

        $discount = $this->transactionFields()->firstWhere('pad_field_id', $discountPadField->id)->value;

        $discountAmount = number_format($this->grandTotalPrice * $discount, 2, thousands_separator:'');

        return Attribute::make(
            get:fn() => number_format(
                $this->grandTotalPrice - $discountAmount,
                2,
                thousands_separator:''
            )
        );
    }
}
