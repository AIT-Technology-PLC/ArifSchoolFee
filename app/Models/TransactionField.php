<?php

namespace App\Models;

use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class TransactionField extends Model
{
    use SoftDeletes, TouchParentUserstamp, HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function padField()
    {
        return $this->belongsTo(PadField::class);
    }

    public function parentModel()
    {
        return $this->transaction;
    }

    public function scopeMasterFields($query)
    {
        return $query->whereNotNull('pad_field_id')->whereNull('line');
    }

    public function scopeDetailFields($query)
    {
        return $query->whereNotNull('pad_field_id')->whereNotNull('line');
    }

    public function relationValue(): Attribute
    {
        return Attribute::make(
            get:function () {
                return is_numeric($this->value)
                ? DB::table(str($this->padField->padRelation->model_name)->lower()->plural())
                    ->find($this->value)
                    ->{$this->padField->padRelation->representative_column}
                : null;
            }
        );
    }

    public function getByWarehouseAndProduct($warehouse, $product)
    {
        $data = collect();

        $transactions = $this
            ->with('transaction')
            ->where(function ($query) {
                $query->where('key', 'subtracted_by')
                    ->orWhere('key', 'added_by');
            })
            ->get()
            ->pluck('transaction')
            ->filter();

        if ($transactions->isNotEmpty()) {
            $transactions
                ->each(function ($transaction) use ($warehouse, $product, $data) {
                    $transaction
                        ->transactionDetails
                        ->each(function ($transactionDetail) use ($warehouse, $product, $data) {
                            if ($transactionDetail['product'] == $product->name && $transactionDetail['warehouse'] == $warehouse->name) {
                                $data->push($transactionDetail);
                            }
                        });
                });
        }

        return $data;
    }
}
