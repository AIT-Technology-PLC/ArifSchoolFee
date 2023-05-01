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
                ? DB::table(str($this->padField->padRelation->model_name)->lower()->plural()->snake())
                    ->whereNull('deleted_at')
                    ->find($this->value)
                    ->{$this->padField->padRelation->representative_column} ?? 'N/A'
                : null;
            }
        );
    }

    public function getByWarehouseAndProduct($warehouse, $product)
    {
        $data = collect();

        $transactions = Transaction::query()
            ->with([
                'transactionFields' => function ($query) {
                    return $query->where(function ($query) {
                        $query->where('key', 'subtracted_by')
                            ->orWhere('key', 'added_by');
                    });
                },
            ])
            ->whereHas('transactionFields', function ($query) {
                return $query->where(function ($query) {
                    $query->where('key', 'subtracted_by')
                        ->orWhere('key', 'added_by');
                });
            })
            ->get();

        if ($transactions->isNotEmpty()) {
            $transactions
                ->each(function ($transaction) use ($warehouse, $product, $data) {
                    $transaction
                        ->transactionDetails
                        ->whereIn('line', $transaction->transactionFields->pluck('line')->unique())
                        ->each(function ($transactionDetail) use ($warehouse, $product, $data) {
                            if ($transactionDetail['product_id'] == $product->id && $transactionDetail['warehouse_id'] == $warehouse->id) {
                                $data->push($transactionDetail);
                            }
                        });
                });
        }

        return $data;
    }

    public static function subtract($transaction, $line)
    {
        static::create([
            'transaction_id' => $transaction->id,
            'key' => 'subtracted_by',
            'value' => authUser()->id,
            'line' => $line,
        ]);
    }

    public static function add($transaction, $line)
    {
        static::create([
            'transaction_id' => $transaction->id,
            'key' => 'added_by',
            'value' => authUser()->id,
            'line' => $line,
        ]);
    }

    public static function isSubtracted($transaction, $line)
    {
        return static::where('transaction_id', $transaction->id)->where('line', $line)->where('key', 'subtracted_by')->exists();
    }

    public static function isAdded($transaction, $line)
    {
        return static::where('transaction_id', $transaction->id)->where('line', $line)->where('key', 'added_by')->exists();
    }
}
