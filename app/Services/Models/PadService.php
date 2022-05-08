<?php

namespace App\Services\Models;

use App\Models\Pad;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PadService
{
    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            $pad = Pad::create(Arr::except($data, ['field']));

            collect($data['field'])
                ->each(function ($field) use ($pad) {
                    $padField = $pad->padFields()->create($field);

                    if ($field['is_relational_field']) {
                        $padField->padRelation()->create(Arr::only($field, ['relationship_type', 'model_name', 'representative_column', 'component_name']));
                    }
                });

            $pad->padPermissions()->createMany($this->generatePermissions($pad));

            $pad->padFields()->createMany($this->generatePaymentTermFields($pad));

            $pad->padFields()->createMany($this->generatePriceFields($pad));

            return $pad;
        });
    }

    public function update($pad, $data)
    {
        return DB::transaction(function () use ($pad, $data) {
            $pad->update(Arr::except($data, ['field']));

            collect($data['field'])
                ->each(function ($field, $i) use ($pad) {
                    $pad->padFields[$i]->update($field);

                    if ($field['is_relational_field']) {
                        $pad->padFields[$i]->padRelation->update(
                            Arr::only($field, ['relationship_type', 'model_name', 'representative_column', 'component_name'])
                        );
                    }
                });

            $pad->padPermissions()->whereNotIn('name', collect($this->generatePermissions($pad))->pluck('name'))->forceDelete();

            collect($this->generatePermissions($pad))
                ->each(function ($permission) use ($pad) {
                    $pad->padPermissions()->firstOrCreate($permission);
                });

            return $pad;
        });
    }

    private function generatePermissions($pad)
    {
        $permissions = [
            ['name' => 'Create'],
            ['name' => 'Read'],
            ['name' => 'Update'],
            ['name' => 'Delete'],
        ];

        if ($pad->isApprovable()) {
            $permissions[] = ['name' => 'Approve'];
        }

        if ($pad->isClosable()) {
            $permissions[] = ['name' => 'Close'];
        }

        if ($pad->isCancellable()) {
            $permissions[] = ['name' => 'Cancel'];
        }

        if (!$pad->isInventoryOperationNone()) {
            $permissions[] = ['name' => ucfirst($pad->getInventoryOperationType())];
        }

        return $permissions;
    }

    private function generatePriceFields($pad)
    {
        if (!$pad->hasPrices()) {
            return [];
        }

        return [
            [
                'label' => 'Unit Price',
                'icon' => 'fas fa-dollar-sign',
                'is_master_field' => 0,
                'is_required' => 1,
                'is_visible' => 1,
                'is_printable' => 1,
                'tag' => 'input',
                'tag_type' => 'number',
            ],
            [
                'label' => 'Quantity',
                'icon' => 'fas fa-balance-scale',
                'is_master_field' => 0,
                'is_required' => 1,
                'is_visible' => 1,
                'is_printable' => 1,
                'tag' => 'input',
                'tag_type' => 'number',
            ],
            [
                'label' => 'Discount',
                'icon' => 'fas fa-percent',
                'is_master_field' => 0,
                'is_required' => 1,
                'is_visible' => 1,
                'is_printable' => 1,
                'tag' => 'input',
                'tag_type' => 'number',
            ],
        ];
    }

    private function generatePaymentTermFields($pad)
    {
        if (!$pad->hasPaymentTerm()) {
            return [];
        }

        return [
            [
                'label' => 'Discount',
                'icon' => 'fas fa-percent',
                'is_master_field' => 1,
                'is_required' => 1,
                'is_visible' => 0,
                'is_printable' => 1,
                'tag' => 'input',
                'tag_type' => 'number',
            ],
            [
                'label' => 'Payment Method',
                'icon' => 'fas fa-credit-card',
                'is_master_field' => 1,
                'is_required' => 1,
                'is_visible' => 1,
                'is_printable' => 1,
                'tag' => 'select',
                'tag_type' => '',
            ],
            [
                'label' => 'Cash Received',
                'icon' => 'fas fa-dollar-bill',
                'is_master_field' => 1,
                'is_required' => 1,
                'is_visible' => 0,
                'is_printable' => 1,
                'tag' => 'input',
                'tag_type' => 'number',
            ],
            [
                'label' => 'Credit Due Date',
                'icon' => 'fas fa-calendar',
                'is_master_field' => 1,
                'is_required' => 1,
                'is_visible' => 0,
                'is_printable' => 1,
                'tag' => 'input',
                'tag_type' => 'date',
            ],
        ];
    }
}
