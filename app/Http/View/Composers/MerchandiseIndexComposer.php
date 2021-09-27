<?php

namespace App\Http\View\Composers;

use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class MerchandiseIndexComposer
{
    public function compose(View $view)
    {
        if (!auth()->check()) {
            return;
        }

        abort_if(
            !Gate::allows('viewAny', Merchandise::class),
            403
        );

        $view->with([
            'warehouses' => Warehouse::orderBy('name')->get(['id', 'name']),
            'insights' => $this->insights(),
            'readNotifications' => auth()->user()->readNotifications,
        ]);
    }

    private function insights()
    {
        return [
            'totalOnHandProducts' => (new Product)->getOnHandMerchandiseProductsQuery()->count(),
            'totalOutOfStockProducts' => (new Product)->getOutOfOnHandMerchandiseProductsQuery()->count(),
            'totalLimitedMerchandises' => (new Product)->getLimitedMerchandiseProductsQuery()->count(),
            'totalWarehousesInUse' => (new Warehouse)->getWarehousesInUseQuery()->count(),
        ];
    }
}
