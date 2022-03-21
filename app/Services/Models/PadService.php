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

            $pad->padFields()->createMany($data['field']);

            $pad->padPermissions()->createMany($this->generatePermissions($pad));

            return $pad;
        });
    }

    public function generatePermissions($pad)
    {
        $permissions = [
            ['name' => 'Create ' . $pad->name],
            ['name' => 'Read ' . $pad->name],
            ['name' => 'Update ' . $pad->name],
            ['name' => 'Delete ' . $pad->name],
        ];

        if ($pad->isApprovable()) {
            $permissions[] = ['name' => 'Approve ' . $pad->name];
        }

        if ($pad->isClosable()) {
            $permissions[] = ['name' => 'Close ' . $pad->name];
        }

        if ($pad->isCancellable()) {
            $permissions[] = ['name' => 'Cancel ' . $pad->name];
        }

        if ($pad->getInventoryOperationType() != 'none') {
            $permissions[] = ['name' => (string) str($pad->getInventoryOperationType())->ucfirst() . ' ' . $pad->name];
        }

        return $permissions;
    }
}
