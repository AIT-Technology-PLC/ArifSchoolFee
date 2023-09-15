<?php

namespace App\Traits;

use App\Models\Customer;
use App\Models\MerchandiseBatch;
use App\Models\Product;
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

    public function transactionStatus(): Attribute
    {
        return Attribute::make(
            get:function () {
                return $this->pad->padStatuses()->active()->firstWhere('name', $this->status) ?? null;
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
                        $data['line'] = $transactionFields->first()->line;

                        foreach ($transactionFields as $transactionField) {
                            $value = $transactionField->padField?->tag_type == 'number'
                            ? number_format($transactionField->value, 2, thousands_separator : '')
                            : $transactionField->value;

                            if ($transactionField->padField->hasRelation()) {
                                $value = $transactionField->relationValue;
                                $data[str($transactionField->padField->padRelation->model_name)->snake()->append('_id')->toString()] = $transactionField->value;
                            }

                            if ($transactionField->padField->isMerchandiseBatchField() && !empty($transactionField->value)) {
                                $data['expires_on'] = MerchandiseBatch::find($transactionField->value)?->expires_on?->toDateString() ?? 'N/A';
                            }

                            if ($transactionField->padField->padRelation?->model_name == 'Product') {
                                $data['unit'] = Product::find($transactionField->value)->unit_of_measurement;
                                $taxAmount = Product::find($transactionField->value)->tax->amount;
                            }

                            $data[str()->snake($transactionField->padField->label)] = $value;
                        }

                        if ($this->pad->hasPrices()) {
                            $data['unit_price'] = number_format($data['unit_price'] ?? 0, 2, thousands_separator:'');

                            $unitPrice = $transactionField->transaction->company->isPriceBeforeTax()
                            ? $data['unit_price']
                            : number_format($data['unit_price'] / (1 + $taxAmount), 2, thousands_separator:'');

                            $data['total'] = number_format($unitPrice * $data['quantity'], 2, thousands_separator:'');

                            $data['total_tax'] = number_format($data['total'] * $taxAmount, 2, thousands_separator:'');
                        }

                        return $data;
                    });
            }
        );
    }

    public function getTransactionDetails()
    {
        return $this->transactionFields()
            ->with('padField.padRelation')
            ->detailFields()
            ->get()
            ->groupBy('line')
            ->map(function ($transactionFields) {
                $data = [];
                $data['id'] = $transactionFields->first()->id;
                $data['transaction'] = $transactionFields->first()->transaction;
                $data['line'] = $transactionFields->first()->line;

                foreach ($transactionFields as $transactionField) {
                    $value = is_numeric($transactionField->value)
                    ? number_format($transactionField->value, 2, thousands_separator : '')
                    : $transactionField->value;

                    if ($transactionField->padField->hasRelation()) {
                        $value = $transactionField->relationValue;
                        $data[str($transactionField->padField->padRelation->model_name)->snake()->append('_id')->toString()] = $transactionField->value;
                    }

                    if ($transactionField->padField->isMerchandiseBatchField() && !empty($transactionField->value)) {
                        $data['expires_on'] = MerchandiseBatch::find($transactionField->value)?->expires_on?->toDateString() ?? 'N/A';
                    }

                    if ($transactionField->padField->padRelation?->model_name == 'Product') {
                        $taxAmount = Product::find($transactionField->value)->tax->amount;
                    }

                    $data[str()->snake($transactionField->padField->label)] = $value;
                }

                if ($this->pad->hasPrices()) {
                    $data['unit_price'] = number_format($data['unit_price'] ?? 0, 2, thousands_separator:'');

                    $unitPrice = $transactionField->transaction->company->isPriceBeforeTax()
                    ? $data['unit_price']
                    : number_format($data['unit_price'] / (1 + $taxAmount), 2, thousands_separator:'');

                    $data['total'] = number_format($unitPrice * $data['quantity'], 2, thousands_separator:'');

                    $data['total_tax'] = number_format($data['total'] * $taxAmount, 2, thousands_separator:'');
                }

                return $data;
            });
    }

    public function transactionMasters(): Attribute
    {
        return Attribute::make(
            get:function () {
                $data = [];
                $this->transactionFields()
                    ->with('padField.padRelation')
                    ->masterFields()
                    ->get()
                    ->each(function ($transactionField) use (&$data) {
                        $data['id'] = $transactionField->id;
                        $data['transaction'] = $transactionField->transaction;

                        $value = $transactionField->value;

                        if ($transactionField->padField->hasRelation()) {
                            $value = $transactionField->relationValue;
                            $data[str($transactionField->padField->padRelation->model_name)->snake()->append('_id')->toString()] = $transactionField->value;
                        }

                        $data[str()->snake($transactionField->padField->label)] = $value;
                    });

                return collect($data);
            }
        );
    }

    public function subtotalPrice(): Attribute
    {
        return Attribute::make(
            get:function () {
                $transactionDetails = $this->transactionDetails;

                $total = $transactionDetails->reduce(function ($carry, $item) {
                    return $carry + (($item['unit_price'] ?? 0) * $item['quantity']);
                });

                return number_format(
                    $total,
                    2,
                    thousands_separator:''
                );
            }
        );
    }

    public function tax(): Attribute
    {
        return Attribute::make(
            get:fn() => number_format(
                $this->transactionDetails->sum('total_tax'),
                2,
                thousands_separator:''
            )
        );
    }

    public function grandTotalPrice(): Attribute
    {
        return Attribute::make(
            get:fn() => number_format(
                $this->subtotalPrice + $this->tax,
                2,
                thousands_separator:''
            )
        );
    }

    public function grandTotalPriceAfterDiscount(): Attribute
    {
        return Attribute::make(
            get:function () {
                return $this->grandTotalPrice;
            }
        );
    }
}
