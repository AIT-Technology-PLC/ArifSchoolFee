<?php

namespace App\View\Components\Common;

use App\Models\Supplier;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class SupplierList extends Component
{
    public $suppliers;

    public $selectedId;

    public $id;

    public $name;

    public $value;

    public function __construct($selectedId, $id = 'supplier_id', $name = 'supplier_id', $value = 'id')
    {
        $this->suppliers = Cache::store('array')->rememberForever(authUser()->id . '_' . 'supplierLists', function () {
            return Supplier::validBusinessLicense()->orderBy('company_name')->get(['id', 'company_name', 'tin']);
        });

        $this->selectedId = $selectedId;

        $this->id = $id;

        $this->name = $name;

        $this->value = $value;
    }

    public function render()
    {
        return view('components.common.supplier-list');
    }
}
