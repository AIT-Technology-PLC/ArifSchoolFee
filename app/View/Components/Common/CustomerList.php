<?php

namespace App\View\Components\Common;

use App\Models\Customer;
use Illuminate\View\Component;

class CustomerList extends Component
{
    public $customers, $selectedCustomerId;

    public function __construct($selectedCustomerId)
    {
        $this->customers = Customer::orderBy('company_name')->get(['id', 'company_name']);

        $this->selectedCustomerId = $selectedCustomerId;
    }

    public function render()
    {
        return view('components.common.customer-list');
    }
}
