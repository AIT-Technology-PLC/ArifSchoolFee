<?php

namespace App\Models;

use App\Traits\Branchable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\TransactionAccessors;
use App\Traits\TransactionConverts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use MultiTenancy, Branchable, SoftDeletes, HasUserstamps, TransactionAccessors, TransactionConverts;

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

    public function scopeSubtracted($query)
    {
        return $query->whereHas('transactionFields', fn($q) => $q->where('key', 'subtracted_by'));
    }

    public function scopeNotSubtracted($query)
    {
        return $query->whereDoesntHave('transactionFields', fn($q) => $q->where('key', 'subtracted_by'));
    }

    public function scopeAdded($query)
    {
        return $query->whereHas('transactionFields', fn($q) => $q->where('key', 'added_by'));
    }

    public function scopeNotAdded($query)
    {
        return $query->whereDoesntHave('transactionFields', fn($q) => $q->where('key', 'added_by'));
    }

    public function scopeApproved($query)
    {
        return $query->whereHas('transactionFields', fn($q) => $q->where('key', 'approved_by'));
    }

    public function scopeNotApproved($query)
    {
        return $query->whereDoesntHave('transactionFields', fn($q) => $q->where('key', 'approved_by'));
    }

    public function isAdded()
    {
        $totalDetails = $this->transactionDetails->count();
        $totalAddedDetails = $this->transactionFields()->where('key', 'added_by')->whereNotNull('line')->count();

        return $totalDetails > 0 && $totalAddedDetails > 0 && $totalDetails == $totalAddedDetails;
    }

    public function isPartiallyAdded()
    {
        $totalDetails = $this->transactionDetails->count();
        $totalAddedDetails = $this->transactionFields()->where('key', 'added_by')->whereNotNull('line')->count();

        return $totalDetails > 0 && $totalAddedDetails > 0 && $totalDetails > $totalAddedDetails;
    }

    public function isSubtracted()
    {
        $totalDetails = $this->transactionDetails->count();
        $totalSubtractedDetails = $this->transactionFields()->where('key', 'subtracted_by')->whereNotNull('line')->count();

        return $totalDetails > 0 && $totalSubtractedDetails > 0 && $totalDetails == $totalSubtractedDetails;
    }

    public function isPartiallySubtracted()
    {
        $totalDetails = $this->transactionDetails->count();
        $totalSubtractedDetails = $this->transactionFields()->where('key', 'subtracted_by')->whereNotNull('line')->count();

        return $totalDetails > 0 && $totalSubtractedDetails > 0 && $totalDetails > $totalSubtractedDetails;
    }

    public function isApproved()
    {
        return $this->transactionFields()->where('key', 'approved_by')->exists();
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
        $subtractedLines = $this->transactionFields()->where('key', 'subtracted_by')->whereNotNull('line')->pluck('line')->unique();

        foreach ($this->transactionDetails->whereNotIn('line', $subtractedLines) as $transactionDetail) {
            $this->transactionFields()->create([
                'key' => 'subtracted_by',
                'value' => authUser()->id,
                'line' => $transactionDetail['line'],
            ]);
        }
    }

    public function add()
    {
        $addedLines = $this->transactionFields()->where('key', 'added_by')->whereNotNull('line')->pluck('line')->unique();

        foreach ($this->transactionDetails->whereNotIn('line', $addedLines) as $transactionDetail) {
            $this->transactionFields()->create([
                'key' => 'added_by',
                'value' => authUser()->id,
                'line' => $transactionDetail['line'],
            ]);
        }
    }

    public function canBeEdited()
    {
        $isEditable = $this->transactionStatus ? $this->transactionStatus->isEditable() : true;

        return !$this->isApproved() && !$this->isAdded() && !$this->isSubtracted() && $isEditable;
    }

    public function canBeDeleted()
    {
        $isDeletable = $this->transactionStatus ? $this->transactionStatus->isDeletable() : true;

        return !$this->isApproved() && !$this->isAdded() && !$this->isSubtracted() && $isDeletable;
    }
}
