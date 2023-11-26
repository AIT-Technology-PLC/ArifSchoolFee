<?php

namespace App\View\Components\Common;

use App\Models\Customer;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class CustomerList extends Component
{
    public $customers;

    public $selectedId;

    public $id;

    public $name;

    public $value;

    public function __construct($selectedId = null, $id = 'customer_id', $name = 'customer_id', $value = 'id')
    {
        $this->customers = Cache::store('array')
            ->rememberForever(authUser()->id . '_' . 'customerLists', function () {
                return Customer::validBusinessLicense()->orderBy('company_name')->get(['id', 'company_name', 'tin']);
            });

        $this->selectedId = $selectedId;

        $this->id = $id;

        $this->name = $name;

        $this->value = $value;
    }

    public function render()
    {
        return view('components.common.customer-list');
    }
}
