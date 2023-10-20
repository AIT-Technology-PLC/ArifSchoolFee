<?php

namespace App\Rules;

use App\Models\CustomField;
use Illuminate\Contracts\Validation\ImplicitRule;

class ValidateCustomFields implements ImplicitRule
{
    public $message;

    public $routeParameter;

    public function __construct($routeParameter)
    {
        $this->routeParameter = $routeParameter;
    }

    public function passes($attribute, $value)
    {
        $customFieldId = str($attribute)->afterLast('.')->toString();

        $customField = CustomField::active()->where('model_type', $this->routeParameter)->find($customFieldId);

        if (!$customField) {
            $this->message = 'This field is not available.';
            return false;
        }

        if ($customField->isRequired() && empty($value)) {
            $this->message = $customField->label . ' is required.';
            return false;
        }

        $doesValueExists = $customField
            ->customFieldValues()
            ->where('value', $value)
            ->when(!empty(request()->route($this->routeParameter)), fn($q) => $q->whereNot('custom_field_valuable_id', request()->route($this->routeParameter)->id))
            ->exists();

        if ($customField->isUnique() && $doesValueExists) {
            $this->message = str($customField->label)->append(' ', $value, ' is already taken.')->toString();
            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->message;
    }
}
