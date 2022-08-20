<?php

namespace App\Models;

use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps, CascadeSoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getUndueDebtAmount()
    {
        $debts = $this->debts()->unsettled()->where('due_date', '>=', today())->get();

        return $debts->sum('debt_amount') - $debts->sum('debt_amount_settled');
    }

    public function getOverdueDebtAmountByPeriod($from, $to = null)
    {
        $debts = $this->debts()->unsettled()
            ->where('due_date', '<=', now()->subDays($from))
            ->when(!is_null($to), fn($q) => $q->where('due_date', '>=', now()->subDays($to)))
            ->get();

        return $debts->sum('debt_amount') - $debts->sum('debt_amount_settled');
    }
}
