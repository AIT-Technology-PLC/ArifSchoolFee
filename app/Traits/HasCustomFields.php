<?php

namespace App\Traits;

use App\Models\CustomField;
use App\Models\CustomFieldValue;
use Illuminate\Support\Arr;

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

    public function printableCustomFields($limit = 1)
    {
        return $this->customFieldValues()->with('customField')->whereHas('customField', fn($q) => $q->printable())->limit($limit)->get();
    }

    public function customFieldsAsKeyValue()
    {
        return $this->customFieldValues()->pluck('value', 'custom_field_id');
    }

    public function createCustomFields($data)
    {
        if (empty($data)) {
            return;
        }

        $data = Arr::whereNotNull($data);

        $this->customFieldValues()->forceDelete();

        foreach ($data as $key => $value) {
            $customFields[] = [
                'custom_field_id' => $key,
                'value' => $value,
            ];
        }

        !empty($customFields) ? $this->customFieldValues()->createMany($customFields) : null;
    }

    public function convertedCustomFields($convertedToFeature)
    {
        $data = [];

        if (!method_exists($this, 'customFieldValues')) {
            return $data;
        }

        foreach ($this->loadMissing(['customFieldValues.customField'])->customFieldValues as $customFieldValue) {
            $customField = CustomField::where('model_type', $convertedToFeature)->where('label', $customFieldValue->customField->label)->first();

            if (empty($customField)) {
                continue;
            }

            $data[$customField->id] = $customFieldValue->value;
        }

        return $data;
    }

    public function storeConvertedCustomFields($sourceModel, $convertedToFeature)
    {
        $this->createCustomFields(
            $sourceModel->convertedCustomFields($convertedToFeature)
        );
    }
}
