<?php

namespace App\View\Components\Common;

use App\Models\FeeDiscount;
use App\Models\FeePayment;
use App\Models\AssignFeeDiscount;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class FeeDiscountList extends Component
{
    public $feeDiscounts;
    public $selectedId;
    public $id;
    public $name;
    public $value;

    public function __construct($selectedId, $id = 'fee_discount_id', $name = 'fee_discount_id', $value = 'id')
    {
        $this->feeDiscounts = Cache::store('array')->rememberForever('feeDiscounts', function () use ($selectedId) {
            $assignedDiscounts = AssignFeeDiscount::where('student_id', $selectedId)->pluck('fee_discount_id')->toArray();

            return FeeDiscount::orderBy('name')->get()->filter(function ($discount) use ($assignedDiscounts, $selectedId) {
                if (in_array($discount->id, $assignedDiscounts)) {
                    if ($discount->discount_type === 'once') {
                        $usedDiscount = FeePayment::where('student_id', $selectedId)
                            ->where('fee_discount_id', $discount->id)
                            ->exists();

                        return !$usedDiscount;
                    }
                }

                return $discount->discount_type === 'year' || !in_array($discount->id, $assignedDiscounts);
            });
        });

        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
    }

    public function render()
    {
        return view('components.common.fee-discount-list');
    }
}
