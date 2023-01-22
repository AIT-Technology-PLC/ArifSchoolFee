<?php

namespace App\Models;

use App\Models\Expense;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps, CascadeSoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'document_expire_on' => 'date',
    ];

    protected $cascadeDeletes = ['debts'];

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

    public function getUndueDebtAmount()
    {
        $debts = $this->debts()->unsettled()->where('due_date', '>=', today())->get();

        return $debts->sum('debt_amount') - $debts->sum('debt_amount_settled');
    }

    public function getOverdueDebtAmountByPeriod($from, $to = null)
    {
        $debts = $this->debts()->unsettled()
            ->where('due_date', '<=', now()->subDays($from)->endOfDay())
            ->when(!is_null($to), fn($q) => $q->where('due_date', '>=', now()->subDays($to)->startOfDay()))
            ->get();

        return $debts->sum('debt_amount') - $debts->sum('debt_amount_settled');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
