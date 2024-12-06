<?php

namespace App\View\Components\Common;

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class PaymentMethodList extends Component
{
    public $paymentMethods;
    public $id;
    public $name;
    public $value;

    public function __construct($id = 'payment_mode', $name = 'payment_mode', $value = 'name')
    {
        $this->paymentMethods = Cache::store('array')->rememberForever('paymentMethods', function () {
            return PaymentMethod::enabled()->orderBy('name')->get(['id', 'name']);
        });

        $this->id = $id;

        $this->name = $name;

        $this->value = $value;
    }

    public function render()
    {
        return view('components.common.payment-method-list');
    }
}
