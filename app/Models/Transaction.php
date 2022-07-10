<?php

namespace App\Models;

use App\Traits\Branchable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\TransactionAccessors;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use MultiTenancy, Branchable, SoftDeletes, HasUserstamps, TransactionAccessors;

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
            'value' => authUser()->id,
        ]);
    }

    public function subtract()
    {
        $this->transactionFields()->create([
            'key' => 'subtracted_by',
            'value' => authUser()->id,
        ]);
    }

    public function add()
    {
        $this->transactionFields()->create([
            'key' => 'added_by',
            'value' => authUser()->id,
        ]);
    }

    public function close()
    {
        $this->transactionFields()->create([
            'key' => 'closed_by',
            'value' => authUser()->id,
        ]);
    }

    public function cancel()
    {
        $this->transactionFields()->create([
            'key' => 'cancelled_by',
            'value' => authUser()->id,
        ]);
    }
}
