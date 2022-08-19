<?php

namespace App\Models;

use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function grns()
    {
        return $this->hasMany(Grn::class);
    }

    public function debts()
    {
        return $this->hasMany(Debt::class);
    }

    public function hasReachedDebtLimit($newDebtAmount, $excludedDebtId = null)
    {
        if ($this->debt_amount_limit == 0) {
            return false;
        }

        $totalDebtAmount = $this->debts()
            ->when($excludedDebtId, fn($query) => $query->where('id', '<>', $excludedDebtId))
            ->sum('debt_amount');

        $totalDebtAmountSettled = $this->debts()
            ->when($excludedDebtId, fn($query) => $query->where('id', '<>', $excludedDebtId))
            ->sum('debt_amount_settled');

        $currentDebtAmount = $totalDebtAmount - $totalDebtAmountSettled;

        if (($currentDebtAmount + $newDebtAmount) > $this->debt_amount_limit) {
            return true;
        }

        return false;
    }
}
