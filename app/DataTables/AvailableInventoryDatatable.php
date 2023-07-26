<?php

namespace App\DataTables;

use App\Models\Merchandise;
use App\Models\ProductReorder;
use App\Services\Inventory\MerchandiseProductService;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AvailableInventoryDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function __construct()
    {
        $this->warehouses = authUser()->getAllowedWarehouses('read');
    }

    public function dataTable($query)
    {
        return $this
            ->editWarehouses(datatables()->collection($query->all()))
            ->editColumn('product', fn($row) => $row['product'])
            ->editColumn('code', fn($row) => $row['code'] ?? 'N/A')
            ->editColumn('description', fn($row) => view('components.datatables.searchable-description', ['description' => $row['description']]))
            ->rawColumns([
                ...$this->warehouses->pluck('name')->toArray(),
                'total balance',
                'product',
            ])
            ->addIndexColumn();
    }

    private function editWarehouses($datatable)
    {
        $this->warehouses->each(function ($warehouse) use ($datatable) {
            $datatable
                ->editColumn($warehouse->name, function ($row) use ($warehouse) {
                    return view('components.datatables.history-link', [
                        'amount' => Arr::has($row, $warehouse->name) ? $row[$warehouse->name] : 0.00,
                        'productId' => $row['product_id'],
                        'warehouseId' => $warehouse->id,
                        'unit' => $row['unit'],
                        'reorderQuantity' => $row['min_on_hand'],
                    ]);
                })
                ->editColumn('total balance', function ($row) {
                    return view('components.datatables.green-solid-tag', [
                        'amount' => $row['total balance'] ?: 0.00,
                        'unit' => $row['unit'],
                    ]);
                });
        });

        return $datatable;
    }

    public function query()
    {
        $limitedProducts = (new MerchandiseProductService)->getLimitedMerchandiseProductsQuery(user:authUser())->pluck('id');

        $availableMerchandises = Merchandise::query()
            ->join('products', 'merchandises.product_id', '=', 'products.id')
            ->join('product_categories', 'products.product_category_id', '=', 'product_categories.id')
            ->join('warehouses', 'merchandises.warehouse_id', '=', 'warehouses.id')
            ->when(request('level') == 'sufficient', fn($query) => $query->whereNotIn('products.id', $limitedProducts))
            ->when(request('level') == 'limited', fn($query) => $query->whereIn('products.id', $limitedProducts))
            ->when(request('type') == 'finished goods', fn($query) => $query->where('products.type', '=', 'Finished Goods'))
            ->when(request('type') == 'raw material', fn($query) => $query->where('products.type', '=', 'Raw Material'))
            ->where('merchandises.available', '>', 0)
            ->whereIn('warehouses.id', authUser()->getAllowedWarehouses('read')->pluck('id'))
            ->where('products.type', '!=', 'Services')
            ->select([
                'merchandises.available as available',
                'products.id as product_id',
                'products.name as product',
                'products.code as code',
                'products.type as type',
                'products.unit_of_measurement as unit',
                'products.min_on_hand as min_on_hand',
                'products.description as description',
                'product_categories.name as category',
                'warehouses.name as warehouse',
            ])
            ->get();

        $availableMerchandises = $availableMerchandises->groupBy('product_id')->map->keyBy('warehouse');

        $organizedAvailableMerchandise = collect();

        foreach ($availableMerchandises as $merchandiseKey => $merchandiseValue) {
            $currentMerchandiseItem = [
                'product' => $merchandiseValue->first()->product,
                'code' => $merchandiseValue->first()->code ?? '',
                'product_id' => $merchandiseValue->first()->product_id,
                'unit' => $merchandiseValue->first()->unit,
                'type' => $merchandiseValue->first()->type,
                'min_on_hand' =>
                ProductReorder::where('product_id', $merchandiseValue->first()->product_id)->exists()
                ? ProductReorder::where('product_id', $merchandiseValue->first()->product_id)->pluck('quantity', 'warehouse_id')->toArray()
                : $merchandiseValue->first()->min_on_hand,

                'description' => $merchandiseValue->first()->description,
                'category' => $merchandiseValue->first()->category,
                'total balance' => $merchandiseValue->sum('available'),
            ];

            foreach ($merchandiseValue as $key => $value) {
                $currentMerchandiseItem = Arr::add($currentMerchandiseItem, $key, $value->available);
            }

            $organizedAvailableMerchandise->push($currentMerchandiseItem);
        }

        return $organizedAvailableMerchandise;
    }

    protected function getColumns()
    {
        $warehouses = $this->warehouses->pluck('name');

        return collect([
            '#' => [
                'sortable' => false,
            ],
            'product',
            'code',
            isFeatureEnabled('Job Management') ? 'type' : null,
            'category',
            ...$warehouses,
            'total balance',
        ])
            ->push(Column::make('description')->visible(false))
            ->filter()
            ->toArray();
    }

    protected function filename()
    {
        return 'InventoryLevel_' . date('YmdHis');
    }
}
