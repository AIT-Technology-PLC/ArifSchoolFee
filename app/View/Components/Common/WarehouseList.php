<?php

namespace App\View\Components\Common;

use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class WarehouseList extends Component
{
    public $warehouses;

    public $selectedId;

    public $id;

    public $name;

    public $value;

    public $type;

    public function __construct($selectedId, $id = 'warehouse_id', $name = 'warehouse_id', $value = 'id', $type = 'transactions')
    {
        $this->type = $type;

        $this->warehouses = Cache::store('array')->rememberForever(authUser()->id . '_' . 'warehouseLists', function () {
            return authUser()->getAllowedWarehouses($this->type);
        });

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
