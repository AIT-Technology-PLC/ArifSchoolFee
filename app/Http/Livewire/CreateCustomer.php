<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use App\Services\Models\CustomerService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateCustomer extends Component
{
    use AuthorizesRequests, WithFileUploads;

    public $customer;

    public function render()
    {
        return view('livewire.create-customer');
    }

    public function store()
    {
        $this->authorize('create', Customer::class);

        $customer = (new CustomerService)->store($this->validate()['customer']);

        $this->dispatchBrowserEvent('customer-created', ['customer' => $customer]);

        $this->reset();
    }

    protected function rules()
    {
        return [
            'customer.company_name' => ['required', 'string', 'max:255'],
            'customer.tin' => ['nullable', 'numeric', 'digits:10', Rule::unique('customers')->where('company_id', userCompany()->id)->withoutTrashed()],
            'customer.address' => ['nullable', 'string', 'max:255'],
            'customer.contact_name' => ['nullable', 'string', 'max:255'],
            'customer.email' => ['nullable', 'string', 'email', 'max:255'],
            'customer.phone' => ['nullable', 'string', 'max:255'],
            'customer.country' => ['nullable', 'string', 'max:255'],
            'customer.credit_amount_limit' => ['sometimes', 'required', 'numeric', 'min:0'],
            'customer.business_license_attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5000'],
            'customer.business_license_expires_on' => ['nullable', 'date'],
        ];
    }
}
