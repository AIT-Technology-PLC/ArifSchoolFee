<?php

namespace App\View\Components\Common;

use App\Models\ProductCategory;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class CategoryList extends Component
{
    public $categories;

    public function __construct()
    {
        $this->categories = Cache::store('array')
            ->rememberForever(authUser()->id.'_'.'categoryLists', function () {
                return ProductCategory::orderBy('name')->get();
            });
    }

    public function render()
    {
        return view('components.common.category-list');
    }
}
