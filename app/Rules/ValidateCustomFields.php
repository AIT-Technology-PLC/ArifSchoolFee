<?php

namespace App\Rules;

use App\Models\CustomField;
use Illuminate\Contracts\Validation\ImplicitRule;

class ValidateCustomFields implements ImplicitRule
{
    public $message;

    public function passes($attribute, $value)
    {
        $customFieldId = str($attribute)->afterLast('.')->toString();

        $customField = CustomField::find($customFieldId);

        if (!$customField) {
            $this->message = 'This custom field does not belong to your company.';
            return false;
        }

        if ($customField->isRequired() && empty($value)) {
            $this->message = $customField->label . ' is required.';
            return false;
        }

        $doesValueExists = $customField->customFieldValues()->where('value', $value)->whereNot('custom_field_valuable_id', request()->route('grn')->id)->exists();

        if ($customField->isUnique() && $doesValueExists) {
            $this->message = str($customField->label)->append(' ', $value, ' is already taken.');
            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->message;
    }
}
