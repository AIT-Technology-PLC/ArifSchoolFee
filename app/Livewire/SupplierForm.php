<?php

namespace App\Livewire;

use App\Models\Supplier;
use App\Services\Models\SupplierService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class SupplierForm extends Component
{
    use AuthorizesRequests, WithFileUploads;

    public $supplier;

    public $hasRedirect = false;

    public $method = 'store';

    public function mount()
    {
        if (!isFeatureEnabled('Supplier Management')) {
            $this->skipRender();
        }

        $this->supplier = $this->supplier?->toArray();

        !empty($this->supplier['debt_amount_limit']) ?: ($this->supplier['debt_amount_limit'] = 0);
    }

    public function render()
    {
        return view('livewire.supplier-form');
    }

    public function store()
    {
        abort_if(!isFeatureEnabled('Supplier Management'), 403);

        $this->authorize('create', Supplier::class);

        $supplier = (new SupplierService)->store($this->validate()['supplier']);

        $this->dispatch('supplier-created', ['supplier' => $supplier]);

        if ($this->hasRedirect) {
            return redirect()->route('suppliers.index');
        }

        $this->reset();

        !empty($this->supplier['debt_amount_limit']) ?: ($this->supplier['debt_amount_limit'] = 0);
    }

    public function update()
    {
        $supplier = Supplier::find($this->supplier['id']);

        $this->authorize('update', $supplier);

        (new SupplierService)->update($supplier, $this->validate()['supplier']);

        return redirect()->route('suppliers.index');
    }

    public function fetchByTin()
    {
        if (empty($this->supplier['tin'])) {
            $this->supplier['tin'] = null;

            return;
        }

        $supplier = Supplier::firstWhere('tin', $this->supplier['tin'] ?? null);

        if (is_null($supplier)) {
            return;
        }

        $this->supplier = $supplier;
    }

    public function fetchByCompanyName()
    {
        if (empty($this->supplier['company_name'])) {
            return;
        }

        $supplier = Supplier::firstWhere('company_name', $this->supplier['company_name'] ?? null);

        if (is_null($supplier)) {
            return;
        }

        $this->supplier = $supplier;
    }

    protected function rules()
    {
        return [
            'supplier.company_name' => ['required', 'string', 'max:255'],
            'supplier.tin' => ['nullable', 'numeric', 'digits:10',
                Rule::unique('suppliers', 'tin')
                    ->where('company_id', userCompany()->id)
                    ->when($this->method == 'update', fn($q) => $q->where('id', '<>', $this->supplier['id']))
                    ->withoutTrashed(),
            ],
            'supplier.address' => ['nullable', 'string', 'max:255'],
            'supplier.contact_name' => ['nullable', 'string', 'max:255'],
            'supplier.email' => ['nullable', 'string', 'email', 'max:255'],
            'supplier.phone' => ['nullable', 'string', 'max:255'],
            'supplier.country' => ['nullable', 'string', 'max:255'],
            'supplier.debt_amount_limit' => ['sometimes', 'required', 'numeric', 'min:0'],
            'supplier.business_license_attachment' => ['nullable',
                Rule::when(
                    $this->method == 'update' && Storage::exists('public\\' . $this->supplier['business_license_attachment']),
                    'string',
                    ['file', 'mimes:jpg,jpeg,png,pdf', 'max:4000']
                ),
            ],
            'supplier.business_license_expires_on' => ['nullable', 'date'],
        ];
    }
}
