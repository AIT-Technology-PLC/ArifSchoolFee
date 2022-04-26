<?php

namespace App\View\Components\Common;

use App\Models\Supplier;
use Illuminate\View\Component;

class SupplierList extends Component
{
    public $suppliers, $selectedId, $id, $name, $value;

    public function __construct($selectedId, $id = 'supplier_id', $name = 'supplier_id', $value = 'id')
    {
        $this->suppliers = Supplier::orderBy('company_name')->get(['id', 'company_name']);

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
