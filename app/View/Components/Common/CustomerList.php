<?php

namespace App\View\Components\Common;

use App\Models\Customer;
use Illuminate\View\Component;

class CustomerList extends Component
{
    public $customers, $selectedCustomerId, $id, $name, $value;

    public function __construct($selectedCustomerId, $id = 'customer_id', $name = 'customer_id', $value = 'id')
    {
        $this->customers = Customer::orderBy('company_name')->get(['id', 'company_name']);

        $this->selectedCustomerId = $selectedCustomerId;

        $this->id = $id;

        $this->name = $name;

        $this->value = $value;
    }

    public function render()
    {
        return view('components.common.customer-list');
    }
}
