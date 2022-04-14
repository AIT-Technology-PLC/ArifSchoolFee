<?php

namespace App\Services\Models;

use App\Models\Pad;
use App\Models\PadRelation;
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
                        $padField->padRelation()->create(Arr::only($field, ['relationship_type', 'model_name', 'primary_key']));
                    }
                });

            $pad->padPermissions()->createMany($this->generatePermissions($pad));

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
                            Arr::only($field, ['relationship_type', 'model_name', 'primary_key'])
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
}
