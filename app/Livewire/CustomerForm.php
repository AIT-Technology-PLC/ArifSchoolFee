<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Services\Models\CustomerService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class CustomerForm extends Component
{
    use AuthorizesRequests, WithFileUploads;

    public $customer;

    public $hasRedirect = false;

    public $method = 'store';

    public function mount()
    {
        if (!isFeatureEnabled('Customer Management')) {
            $this->skipRender();
        }

        $this->customer = $this->customer?->toArray();

        !empty($this->customer['credit_amount_limit']) ?: ($this->customer['credit_amount_limit'] = 0);
    }

    public function render()
    {
        return view('livewire.customer-form');
    }

    public function store()
    {
        abort_if(!isFeatureEnabled('Customer Management'), 403);

        $this->authorize('create', Customer::class);

        $customer = (new CustomerService)->store($this->validate()['customer']);

        $this->dispatch('customer-created', ['customer' => $customer]);

        if ($this->hasRedirect) {
            return redirect()->route('customers.index');
        }

        $this->reset();

        !empty($this->customer['credit_amount_limit']) ?: ($this->customer['credit_amount_limit'] = 0);
    }

    public function update()
    {
        $customer = Customer::find($this->customer['id']);

        $this->authorize('update', $customer);

        (new CustomerService)->update($customer, $this->validate()['customer']);

        return redirect()->route('customers.index');
    }

    public function fetchByTin()
    {
        if (empty($this->customer['tin'])) {
            $this->customer['tin'] = null;

            return;
        }

        $customer = Customer::firstWhere('tin', $this->customer['tin'] ?? null);

        if (is_null($customer)) {
            return;
        }

        $this->customer = $customer;
    }

    public function fetchByCompanyName()
    {
        if (empty($this->customer['company_name'])) {
            return;
        }

        $customer = Customer::firstWhere('company_name', $this->customer['company_name'] ?? null);

        if (is_null($customer)) {
            return;
        }

        $this->customer = $customer;
    }

    protected function rules()
    {
        return [
            'customer.company_name' => ['required', 'string', 'max:255'],
            'customer.tin' => ['nullable', 'numeric', 'digits:10',
                Rule::unique('customers', 'tin')
                    ->where('company_id', userCompany()->id)
                    ->when($this->method == 'update', fn($q) => $q->where('id', '<>', $this->customer['id']))
                    ->withoutTrashed(),
            ],
            'customer.address' => ['nullable', 'string', 'max:255'],
            'customer.contact_name' => ['nullable', 'string', 'max:255'],
            'customer.email' => ['nullable', 'string', 'email', 'max:255'],
            'customer.phone' => ['nullable', 'string', 'max:255'],
            'customer.country' => ['nullable', 'string', 'max:255'],
            'customer.credit_amount_limit' => ['sometimes', 'required', 'numeric', 'min:0'],
            'customer.business_license_attachment' => ['nullable',
                Rule::when(
                    $this->method == 'update' && Storage::exists('public\\' . $this->customer['business_license_attachment']),
                    'string',
                    ['file', 'mimes:jpg,jpeg,png,pdf', 'max:5000']
                ),
            ],
            'customer.business_license_expires_on' => ['nullable', 'date'],
        ];
    }
}
