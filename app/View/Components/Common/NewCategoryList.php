<?php

namespace App\View\Components\Common;

use App\Models\ProductCategory;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class NewCategoryList extends Component
{
    public $categories;

    public $selectedId;

    public $id;

    public $name;

    public $value;

    public function __construct($selectedId = null, $id = 'product_category_id', $name = 'product_category_id', $value = 'id')
    {
        $this->categories = Cache::store('array')
            ->rememberForever(authUser()->id . '_' . 'newCategoryLists', function () {
                return ProductCategory::orderBy('name')->get(['id', 'name']);
            });

        $this->selectedId = $selectedId;

        $this->id = $id;

        $this->name = $name;

        $this->value = $value;
    }

    public function render()
    {
        return view('components.common.new-category-list');
    }
}
