<?php

namespace App\Rules;

use App\Models\CustomField;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateCustomFields implements ValidationRule
{
    private $routeParameter;

    public $implicit = true;
    public function __construct($routeParameter)
    {
        $this->routeParameter = $routeParameter;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $customFieldId = str($attribute)->afterLast('.')->toString();

        $customField = CustomField::active()->where('model_type', $this->routeParameter)->find($customFieldId);

        if (!$customField) {
            $fail('This field is not available.');
            return;
        }

        if ($customField->isRequired() && empty($value)) {
            $fail($customField->label . ' is required.');
            return;
        }

        $doesValueExists = $customField
            ->customFieldValues()
            ->where('value', $value)
            ->where('warehouse_id', authUser()->warehouse_id)
            ->when(!empty(request()->route($this->routeParameter)), fn($q) => $q->whereNot('custom_field_valuable_id', request()->route($this->routeParameter)->id))
            ->exists();

        if ($customField->isUnique() && !empty($value) && $doesValueExists) {
            $fail(str($customField->label)->append(' ', $value, ' is already taken.')->toString());
        }
    }
}
