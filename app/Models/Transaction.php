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

    public function approve()
    {
        $this->transactionFields()->create([
            'key' => 'approved_by',
            'value' => auth()->id(),
        ]);
    }

    public function subtract()
    {
        $this->transactionFields()->create([
            'key' => 'subtracted_by',
            'value' => auth()->id(),
        ]);
    }

    public function add()
    {
        $this->transactionFields()->create([
            'key' => 'added_by',
            'value' => auth()->id(),
        ]);
    }

    public function close()
    {
        $this->transactionFields()->create([
            'key' => 'closed_by',
            'value' => auth()->id(),
        ]);
    }

    public function cancel()
    {
        $this->transactionFields()->create([
            'key' => 'cancelled_by',
            'value' => auth()->id(),
        ]);
    }

    public function transactionDetails(): Attribute
    {
        return Attribute::make(
            get:function () {
                $padFields = $this->pad->padFields()->detailFields()->get();

                $groupedTransactionFields = $this->transactionFields()->with('padField.padRelation')->whereIn('pad_field_id', $padFields->pluck('id'))->get()->groupBy('line');

                return $groupedTransactionFields->map(function ($groupedTransactionField) {
                    $data = [];

                    foreach ($groupedTransactionField as $transactionField) {
                        $value = $transactionField->value;

                        if ($transactionField->padField->hasRelation()) {
                            $value = $transactionField->relationValue;
                        }

                        $data[str()->snake($transactionField->padField->label)] = $value;
                    }

                    if ($this->pad->hasPrices()) {
                        $data['discount'] = $data['discount'] ?? 0.00;

                        $unitPrice = userCompany()->isPriceBeforeVAT() ? $data['unit_price'] : $data['unit_price'] / 1.15;

                        $data['total'] = number_format($unitPrice * $data['quantity'], 2, thousands_separator:'');

                        $discount = userCompany()->isDiscountBeforeVAT() ? $data['discount'] / 100 : 0.00;

                        $discountAmount = number_format($data['total'] * $discount, 2, thousands_separator:'');

                        $data['discount'] = number_format($discount * 100, 2) . '%';

                        $data['total'] = number_format($data['total'] - $discountAmount, 2, thousands_separator:'');
                    }

                    $data['id'] = $groupedTransactionField->first()->id;
                    $data['transaction'] = $groupedTransactionField->first()->transaction;

                    return $data;
                });
            }
        );
    }

    public function subtotalPrice(): Attribute
    {
        return Attribute::make(
            get:function () {
                $transactionDetails = $this->transactionDetails;

                $total = $transactionDetails->reduce(function ($carry, $item) {
                    return $carry + ($item['unit_price'] * $item['quantity']);
                });

                return number_format(
                    $total,
                    2,
                    thousands_separator:''
                );
            }
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
        return Attribute::make(
            get:function () {
                $discountPadField = $this->pad->padFields()->masterFields()->where('label', 'Discount')->first();

                $discount = $this->transactionFields()->firstWhere('pad_field_id', $discountPadField->id)->value / 100;

                $discountAmount = number_format($this->grandTotalPrice * $discount, 2, thousands_separator:'');

                return number_format(
                    $this->grandTotalPrice - $discountAmount,
                    2,
                    thousands_separator:''
                );
            }
        );
    }
}
