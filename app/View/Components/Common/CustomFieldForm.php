<?php

namespace App\View\Components\Common;

use App\Models\CustomField;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class CustomFieldForm extends Component
{
    public $customFields;

    public $modelType;

    public $input;

    public function __construct($modelType = null, $input = [])
    {
        $this->modelType = $modelType;

        $this->input = $input;

        $cacheName = str('customFields')->append(authUser()->id, 'customFields', $this->modelType)->toString();

        $this->customFields = Cache::store('array')->rememberForever($cacheName, function () {
            return CustomField::query()
                ->active()
                ->when(!empty($this->modelType), fn($q) => $q->where('model_type', $this->modelType))
                ->get();
        });
    }

    public function render()
    {
        return view('components.common.custom-field-form');
    }
}
