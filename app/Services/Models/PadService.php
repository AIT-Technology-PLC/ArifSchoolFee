<?php

namespace App\Services\Models;

use App\Models\PadField;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PadService
{
    public function store($data, $company = null)
    {
        if (is_null($company)) {
            $company = userCompany();
        }

        return DB::transaction(function () use ($data, $company) {
            $pad = $company->pads()->create(Arr::except($data, ['field']));

            if (isset($data['status'])) {
                $pad->padStatuses()->createMany($data['status']);
            }

            $pad->padFields()->createMany(
                Arr::where($data['field'] ?? [], fn($field) => !$field['is_relational_field'])
            );

            $this->createInventoryFields($pad);

            $this->createModelFields($pad, $data['field'] ?? null);

            $this->createOrUpdatePadPermissions($pad);

            $this->createPriceFields($pad);

            $this->createBatchFieldsForAddingPads($pad);

            $this->createBatchFieldsForNonAddingPads($pad);

            return $pad;
        });
    }

    public function update($pad, $data)
    {
        return DB::transaction(function () use ($pad, $data) {
            $pad->update(Arr::except($data, ['field', 'status', 'inventory_operation_type']));

            if (isset($data['status'])) {
                $pad->padStatuses()->forceDelete();
                $pad->padStatuses()->createMany($data['status']);
            }

            $pad->padFields()->createMany(
                Arr::where($data['field'], fn($field) => is_null($field['id']) && !$field['is_relational_field'])
            );

            foreach ($data['field'] as $field) {
                if (is_null($field['id']) || $field['is_relational_field']) {
                    continue;
                }

                $padField = PadField::whereDoesntHave('padRelation')->find($field['id']);

                if (is_null($padField)) {
                    continue;
                }

                $padField->update($field);
            }

            $this->createModelFields($pad, $data['field'] ?? null);

            $this->createOrUpdatePadPermissions($pad, false);

            $this->createPriceFields($pad);

            return $pad;
        });
    }

    private function createOrUpdatePadPermissions($pad, $isCreate = true)
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

        if ($pad->isConvertable()) {
            $permissions[] = ['name' => 'Convert'];
        }

        if (!$pad->isInventoryOperationNone()) {
            $permissions[] = ['name' => ucfirst($pad->getInventoryOperationType())];
        }

        if ($pad->padStatuses()->exists()) {
            $permissions[] = ['name' => 'Update Status'];
        }

        if ($isCreate) {
            $pad->padPermissions()->createMany($permissions);
        }

        if (!$isCreate) {
            $pad->padPermissions()->whereNotIn('name', Arr::pluck($permissions, 'name'))->forceDelete();

            collect($permissions)->each(function ($permission) use ($pad) {
                $pad->padPermissions()->firstOrCreate($permission);
            });
        }
    }

    private function createPriceFields($pad)
    {
        $fields = [];

        if ($pad->isInventoryOperationNone() && $pad->hasPrices()) {
            $fields[] = [
                'label' => 'Quantity',
                'icon' => 'fas fa-balance-scale',
                'is_master_field' => 0,
                'is_required' => 1,
                'is_visible' => 1,
                'is_printable' => 1,
                'is_readonly' => 0,
                'tag' => 'input',
                'tag_type' => 'number',
            ];
        }

        $fields[] = [
            'label' => 'Unit Price',
            'icon' => 'fas fa-dollar-sign',
            'is_master_field' => 0,
            'is_required' => 1,
            'is_visible' => 1,
            'is_printable' => 1,
            'is_readonly' => 0,
            'tag' => 'input',
            'tag_type' => 'number',
        ];

        if (!$pad->hasPrices()) {
            $pad->padFields()->whereIn('label', Arr::pluck($fields, 'label'))->forceDelete();
            return;
        }

        foreach ($fields as $field) {
            $pad->padFields()->firstOrCreate($field);
        }
    }

    private function createBatchFieldsForAddingPads($pad)
    {
        if (!$pad->isInventoryOperationAdd()) {
            return;
        }

        $fields = [
            [
                'label' => 'Batch No',
                'icon' => 'fas fa-th',
                'is_master_field' => 0,
                'is_required' => 0,
                'is_visible' => 0,
                'is_printable' => 0,
                'is_readonly' => 0,
                'tag' => 'input',
                'tag_type' => 'text',
            ],
            [
                'label' => 'Expires On',
                'icon' => 'fas fa-calendar-alt',
                'is_master_field' => 0,
                'is_required' => 0,
                'is_visible' => 0,
                'is_printable' => 0,
                'is_readonly' => 0,
                'tag' => 'input',
                'tag_type' => 'text',
            ],
        ];

        $pad->padFields()->createMany($fields);
    }

    private function createBatchFieldsForNonAddingPads($pad)
    {
        if ($pad->isInventoryOperationAdd()) {
            return;
        }

        $padField = [
            'label' => 'Batch',
            'icon' => 'fas fa-th',
            'is_master_field' => 0,
            'is_required' => 0,
            'is_visible' => 0,
            'is_printable' => 0,
            'is_readonly' => 0,
            'tag' => 'select',
            'tag_type' => '',
        ];

        $padRelation = [
            'is_relational_field' => 1,
            'relationship_type' => 'Belongs To',
            'model_name' => 'Merchandise Batch',
            'representative_column' => 'batch_no',
            'component_name' => 'common.batch-list',
        ];

        $pad->padFields()->create($padField)->padRelation()->create($padRelation);
    }

    private function createInventoryFields($pad)
    {
        if ($pad->isInventoryOperationNone()) {
            return;
        }

        $fields = [
            [
                'label' => 'Product',
                'icon' => 'fas fa-th',
                'is_master_field' => 0,
                'is_required' => 1,
                'is_visible' => 1,
                'is_printable' => 1,
                'is_readonly' => 0,
                'tag' => 'select',
                'tag_type' => '',
                'is_relational_field' => 1,
                'relationship_type' => 'Belongs To',
                'model_name' => 'Product',
                'representative_column' => 'name',
                'component_name' => 'common.product-list',
            ],
            [
                'label' => $pad->isInventoryOperationAdd() ? 'To' : 'From',
                'icon' => 'fas fa-warehouse',
                'is_master_field' => 0,
                'is_required' => 1,
                'is_visible' => 1,
                'is_printable' => 1,
                'is_readonly' => 0,
                'tag' => 'select',
                'tag_type' => '',
                'is_relational_field' => 1,
                'relationship_type' => 'Belongs To',
                'model_name' => 'Warehouse',
                'representative_column' => 'name',
                'component_name' => 'common.warehouse-list',
            ],
            [
                'label' => 'Quantity',
                'icon' => 'fas fa-balance-scale',
                'is_master_field' => 0,
                'is_required' => 1,
                'is_visible' => 1,
                'is_printable' => 1,
                'is_readonly' => 0,
                'tag' => 'input',
                'tag_type' => 'number',
                'is_relational_field' => 0,
            ],
            [
                'label' => 'Remark',
                'icon' => 'fas fa-info',
                'is_master_field' => 0,
                'is_required' => 0,
                'is_visible' => 0,
                'is_printable' => 0,
                'is_readonly' => 0,
                'tag' => 'input',
                'tag_type' => 'text',
                'is_relational_field' => 0,
            ],
        ];

        foreach ($fields as $field) {
            $padField = $pad->padFields()->create($field);

            if ($field['is_relational_field']) {
                $padField->padRelation()->create(
                    Arr::only($field, ['relationship_type', 'model_name', 'representative_column', 'component_name'])
                );
            }
        }
    }

    private function createModelFields($pad, $data)
    {
        if (empty($data)) {
            return;
        }

        $fields = [
            'supplier' => [
                'label' => 'Supplier',
                'icon' => 'fas fa-user-card',
                'tag' => 'select',
                'tag_type' => '',
                'is_relational_field' => 1,
                'relationship_type' => 'Belongs To',
                'model_name' => 'Supplier',
                'representative_column' => 'company_name',
                'component_name' => 'common.supplier-list',
            ],
            'customer' => [
                'label' => 'Customer',
                'icon' => 'fas fa-user',
                'tag' => 'select',
                'tag_type' => '',
                'is_relational_field' => 1,
                'relationship_type' => 'Belongs To',
                'model_name' => 'Customer',
                'representative_column' => 'company_name',
                'component_name' => 'common.customer-list',
            ],
            'user' => [
                'label' => 'User',
                'icon' => 'fas fa-users',
                'tag' => 'select',
                'tag_type' => '',
                'is_relational_field' => 1,
                'relationship_type' => 'Belongs To',
                'model_name' => 'User',
                'representative_column' => 'name',
                'component_name' => 'common.user-list',
            ],
            'warehouse' => [
                'label' => 'Warehouse',
                'icon' => 'fas fa-warehouses',
                'tag' => 'select',
                'tag_type' => '',
                'is_relational_field' => 1,
                'relationship_type' => 'Belongs To',
                'model_name' => 'Warehouse',
                'representative_column' => 'name',
                'component_name' => 'common.warehouse-list',
            ],
            'product' => [
                'label' => 'Product',
                'icon' => 'fas fa-th',
                'tag' => 'select',
                'tag_type' => '',
                'is_relational_field' => 1,
                'relationship_type' => 'Belongs To',
                'model_name' => 'Product',
                'representative_column' => 'name',
                'component_name' => 'common.product-list',
            ],
            'contact' => [
                'label' => 'Contact',
                'icon' => 'fas fa-user',
                'tag' => 'select',
                'tag_type' => '',
                'is_relational_field' => 1,
                'relationship_type' => 'Belongs To',
                'model_name' => 'Contact',
                'representative_column' => 'name',
                'component_name' => 'common.contact-list',
            ],
        ];

        foreach ($data as $item) {
            if (empty($item['list'])) {
                continue;
            }

            $neededField = $fields[$item['list']];

            unset($item['list']);

            $neededField = array_merge($neededField, $item);

            $pad
                ->padFields()
                ->create($neededField)
                ->padRelation()
                ->create(
                    Arr::only($neededField, ['relationship_type', 'model_name', 'representative_column', 'component_name'])
                );
        }
    }
}
