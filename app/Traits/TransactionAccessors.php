<?php

namespace App\Traits;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait TransactionAccessors
{
    public function approvedBy(): Attribute
    {
        return Attribute::make(
            get:function () {
                $id = $this->transactionFields()->where('key', 'approved_by')->first()->value ?? null;

                return $id ? User::find($id) : null;
            });
    }

    public function cancelledBy(): Attribute
    {
        return Attribute::make(
            get:function () {
                $id = $this->transactionFields()->where('key', 'cancelled_by')->first()->value ?? null;

                return $id ? User::find($id) : null;
            });
    }

    public function addedBy(): Attribute
    {
        return Attribute::make(
            get:function () {
                $id = $this->transactionFields()->where('key', 'added_by')->first()->value ?? null;

                return $id ? User::find($id) : null;
            });
    }

    public function subtractedBy(): Attribute
    {
        return Attribute::make(
            get:function () {
                $id = $this->transactionFields()->where('key', 'subtracted_by')->first()->value ?? null;

                return $id ? User::find($id) : null;
            });
    }

    public function closedBy(): Attribute
    {
        return Attribute::make(
            get:function () {
                $id = $this->transactionFields()->where('key', 'closed_by')->first()->value ?? null;

                return $id ? User::find($id) : null;
            });
    }

    public function customer(): Attribute
    {
        return Attribute::make(
            get:function () {
                $id = $this->transactionFields()->masterFields()->where('key', 'customer')->first()->value ?? null;

                return $id ? Customer::find($id) : null;
            });
    }

    public function paymentType(): Attribute
    {
        return Attribute::make(
            get:function () {
                $padFieldId = $this->pad->padFields()->where('label', 'Payment Method')->first()->id ?? null;

                return $padFieldId
                ? $this->transactionFields()->masterFields()->where('pad_field_id', $padFieldId)->first()->value
                : null;
            });
    }

    public function transactionDetails(): Attribute
    {
        return Attribute::make(
            get:function () {
                return $this->transactionFields()
                    ->with('padField.padRelation')
                    ->detailFields()
                    ->get()
                    ->groupBy('line')
                    ->map(function ($transactionFields) {
                        $data = [];
                        $data['id'] = $transactionFields->first()->id;
                        $data['transaction'] = $transactionFields->first()->transaction;

                        foreach ($transactionFields as $transactionField) {
                            $value = $transactionField->value;

                            if ($transactionField->padField->hasRelation()) {
                                $value = $transactionField->relationValue;
                            }

                            $data[str()->snake($transactionField->padField->label)] = $value;
                        }

                        if ($this->pad->hasPrices()) {
                            $data['quantity'] = number_format($data['quantity'], 2, thousands_separator:'');
                            $data['unit_price'] = number_format($data['unit_price'], 2, thousands_separator:'');
                            $data['discount'] = $data['discount'] ?? 0.00;

                            $unitPrice = userCompany()->isPriceBeforeVAT() ? $data['unit_price'] : number_format($data['unit_price'] / 1.15, 2, thousands_separator:'');
                            $data['total'] = number_format($unitPrice * $data['quantity'], 2, thousands_separator:'');
                            $discount = userCompany()->isDiscountBeforeVAT() ? $data['discount'] / 100 : 0.00;
                            $discountAmount = number_format($data['total'] * $discount, 2, thousands_separator:'');
                            $data['discount'] = number_format($discount * 100, 2) . '%';
                            $data['total'] = number_format($data['total'] - $discountAmount, 2, thousands_separator:'');
                        }

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
                $discount = 0.00;

                $discountPadField = $this->pad->padFields()->masterFields()->where('label', 'Discount')->first();

                if ($discountPadField) {
                    $discount = ($this->transactionFields()->firstWhere('pad_field_id', $discountPadField->id)->value) ?? 0.00 / 100;
                }

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
