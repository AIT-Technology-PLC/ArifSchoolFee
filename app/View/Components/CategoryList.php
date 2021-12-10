<?php

namespace App\View\Components;

use App\Models\ProductCategory;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class CategoryList extends Component
{
    public $categories;

    public function __construct()
    {
        $this->categories = Cache::store('array')
            ->rememberForever(auth()->id() . '_' . 'categoryLists', function () {
                return ProductCategory::orderBy('name')->get();
            });
    }

    public function render()
    {
        return view('components.category-list');
    }
}
