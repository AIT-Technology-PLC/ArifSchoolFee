<?php

namespace App\View\Components\Common;

use App\Models\Warehouse;
use Illuminate\View\Component;

class WarehouseList extends Component
{
    public $warehouses, $selectedId, $id, $name, $value;

    public function __construct($selectedId, $id = 'warehouse_id', $name = 'warehouse_id', $value = 'id')
    {
        $this->warehouses = Warehouse::orderBy('name')->get(['id', 'name']);

        $this->selectedId = $selectedId;

        $this->id = $id;

        $this->name = $name;

        $this->value = $value;
    }

    public function render()
    {
        return view('components.common.warehouse-list');
    }
}
