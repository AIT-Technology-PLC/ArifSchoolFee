<?php

namespace App\Traits;

use App\Models\CustomFieldValue;

trait HasCustomFields
{
    public function customFieldValues()
    {
        return $this->morphMany(CustomFieldValue::class, 'custom_field_valuable')->whereHas('customField', fn($q) => $q->active());
    }

    public function customFieldValue($customFieldId)
    {
        return $this->customFieldValues()->with('customField')->where('custom_field_id', $customFieldId)->first()->value ?? 'N/A';
    }

    public function printableCustomFields()
    {
        return $this->customFieldValues()->with('customField')->whereHas('customField', fn($q) => $q->printable())->get();
    }

    public function customFieldsAsKeyValue()
    {
        return $this->customFieldValues()->pluck('value', 'custom_field_id');
    }

    public function createCustomFields($data)
    {
        $data = array_filter($data);

        if (empty($data)) {
            return;
        }

        $this->customFieldValues()->forceDelete();

        foreach ($data as $key => $value) {
            $customFields[] = [
                'custom_field_id' => $key,
                'value' => $value,
            ];
        }

        !empty($customFields) ? $this->customFieldValues()->createMany($customFields) : null;
    }
}
