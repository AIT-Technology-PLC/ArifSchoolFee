<?php

namespace App\Services\Models;

use App\Services\Inventory\InventoryOperationService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class JobService
{
    public function addToWorkInProcess($data, $job, $user)
    {
        if (!$user->hasWarehousePermission('subtract', $job->factory_id)) {
            return [false, 'You do not have permission to subtract from one or more of the warehouses.'];
        }

        if (!$job->isApproved()) {
            return [false, 'This job is not approved yet.', ''];
        }

        if ($job->isClosed()) {
            return [false, 'This Job is already closed.'];
        }

        return DB::transaction(function () use ($data, $job) {
            for ($i = 0; $i < count($job->jobDetails); $i++) {
                if (!isset($data[$i])) {
                    continue;
                }

                if ($data[$i]['product_id'] != $job->jobDetails[$i]->product_id) {
                    continue;
                }

                if (!$job->jobDetails[$i]->canAddToWip()) {
                    continue;
                }

                if (!$this->isQuantityValid($job->jobDetails[$i]->quantity, $job->jobDetails[$i]->available, $job->jobDetails[$i]->wip + $data[$i]['wip'])) {
                    DB::rollBack();
                    return [false, 'The quantity provided is inaccurate.'];
                }

                $job->jobDetails[$i]->update([
                    'product_id' => $data[$i]['product_id'],
                    'wip' => $data[$i]['wip'] + $job->jobDetails[$i]->wip,
                ]);

                $billOfMaterialdetails = $job->jobDetails[$i]->billOfMaterial->billOfMaterialDetails()->get(['product_id', 'quantity'])->toArray();
                $billOfMaterialdetails = data_set($billOfMaterialdetails, '*.warehouse_id', $job->factory_id);
                $quantity = $data[$i]['wip'];

                $details[] = collect($billOfMaterialdetails)->transform(function ($detail) use ($quantity) {
                    $detail['quantity'] = $detail['quantity'] * $quantity;

                    return $detail;
                });

                $addDetails[] = [
                    'product_id' => $data[$i]['product_id'],
                    'quantity' => $data[$i]['wip'],
                    'warehouse_id' => $job->factory_id,
                ];
            }

            if (!isset($details) || !count($details)) {
                return false;
            }

            $billOfMaterialdetails = Arr::flatten($details, 1);

            if (InventoryOperationService::unavailableProducts($billOfMaterialdetails)->isNotEmpty()) {
                DB::rollBack();
                return [false, InventoryOperationService::unavailableProducts($billOfMaterialdetails)];
            }

            if (isset($billOfMaterialdetails) && count($billOfMaterialdetails)) {
                InventoryOperationService::subtract($billOfMaterialdetails);
            }

            if (isset($addDetails) && count($addDetails)) {
                InventoryOperationService::add($addDetails, 'wip');
            }

            return [true, ''];
        });
    }

    public function addToAvailable($data, $job, $user)
    {
        if (!$user->hasWarehousePermission('add', $job->factory_id)) {
            return [false, 'You do not have permission to add to one or more of the warehouses.'];
        }

        if (!$job->isApproved()) {
            return [false, 'This job is not approved yet.', ''];
        }

        if ($job->isClosed()) {
            return [false, 'This Job is already closed.'];
        }

        return DB::transaction(function () use ($data, $job) {
            for ($i = 0; $i < count($job->jobDetails); $i++) {
                if (!isset($data[$i])) {
                    continue;
                }

                if ($data[$i]['product_id'] != $job->jobDetails[$i]->product_id) {
                    continue;
                }

                if ($job->jobDetails[$i]->isCompleted()) {
                    continue;
                }

                if (!$this->isQuantityValid($job->jobDetails[$i]->quantity, $job->jobDetails[$i]->available + $data[$i]['available'], 0)) {
                    DB::rollBack();
                    return [false, 'The quantity provided is inaccurate.'];
                }

                if ($data[$i]['available'] > $job->jobDetails[$i]->wip) {
                    $quantity = $data[$i]['available'] - $job->jobDetails[$i]->wip;

                    $availableDetails[$i] = [
                        'product_id' => $data[$i]['product_id'],
                        'wip' => 0,
                        'available' => $data[$i]['available'] + $job->jobDetails[$i]->available,
                        'quantity' => $quantity,
                        'warehouse_id' => $job->factory_id,
                    ];

                    $wipDetails[$i] = [
                        'product_id' => $data[$i]['product_id'],
                        'quantity' => $job->jobDetails[$i]->wip,
                        'warehouse_id' => $job->factory_id,
                    ];

                    $job->jobDetails[$i]->update(Arr::only($availableDetails[$i], ['product_id', 'wip', 'available']));

                    $billOfMaterialdetails = $job->jobDetails[$i]->billOfMaterial->billOfMaterialDetails()->get(['product_id', 'quantity'])->toArray();
                    $billOfMaterialdetails = data_set($billOfMaterialdetails, '*.warehouse_id', $job->factory_id);

                    $details[$i] = collect($billOfMaterialdetails)->transform(function ($detail) use ($quantity) {
                        $detail['quantity'] = $detail['quantity'] * $quantity;

                        return $detail;
                    });
                }

                if ($data[$i]['available'] <= $job->jobDetails[$i]->wip) {
                    $quantity = $data[$i]['available'];

                    $wipDetails[$i] = [
                        'product_id' => $data[$i]['product_id'],
                        'wip' => $job->jobDetails[$i]->wip - $data[$i]['available'],
                        'available' => $data[$i]['available'] + $job->jobDetails[$i]->available,
                        'quantity' => $quantity,
                        'warehouse_id' => $job->factory_id,
                    ];

                    $job->jobDetails[$i]->update(Arr::only($wipDetails[$i], ['product_id', 'wip', 'available']));
                }
            }

            if (isset($details) && count($details)) {
                $billOfMaterialdetails = Arr::flatten($details, 1);

                if (InventoryOperationService::unavailableProducts($billOfMaterialdetails)->isNotEmpty()) {
                    DB::rollBack();
                    return [false, InventoryOperationService::unavailableProducts($billOfMaterialdetails)];
                }

                InventoryOperationService::subtract($billOfMaterialdetails);
            }

            if (isset($wipDetails) && count($wipDetails)) {
                InventoryOperationService::subtract($wipDetails, 'wip');
                InventoryOperationService::add($wipDetails);
            }

            if (isset($availableDetails) && count($availableDetails)) {
                InventoryOperationService::add($availableDetails);
            }

            if (userCompany()->allowChassisTracker()) {
                foreach ($data as &$chassisTracker) {
                    data_set($chassisTracker, 'chassisTracker.*.product_id', $chassisTracker['product_id']);
                    data_set($chassisTracker, 'chassisTracker.*.warehouse_id', $job->factory_id);
                }

                $chassisTrackers = data_get($data, '*.chassisTracker');

                foreach ($job->jobDetails as $jobDetail) {
                    $chassisTrackers = collect($chassisTrackers)
                        ->flatten(1)
                        ->where('product_id', $jobDetail['product_id'])
                        ->all();

                    $jobDetail->chassisNumbers()->createMany($chassisTrackers);
                }
            }

            return [true, ''];
        });
    }

    public function addExtra($jobExtra, $user)
    {
        if (!$user->hasWarehousePermission('add', $jobExtra->job->factory_id)) {
            return [false, 'You do not have permission to add to one or more of the warehouses.'];
        }

        if ($jobExtra->job->isClosed()) {
            return [false, 'This Job is already closed.'];
        }

        if ($jobExtra->isAdded()) {
            return [false, 'This Product is already added to inventory.'];
        }

        $detail = $jobExtra->only(['product_id', 'quantity']);

        $detail['warehouse_id'] = $jobExtra->job->factory_id;

        DB::transaction(function () use ($jobExtra, $detail) {
            InventoryOperationService::add($detail);

            $jobExtra->add();
        });

        return [true, ''];
    }

    public function subtractExtra($jobExtra, $user)
    {
        if (!$user->hasWarehousePermission('subtract', $jobExtra->job->factory_id)) {
            return [false, 'You do not have permission to subtract from one or more of the warehouses.'];
        }

        if ($jobExtra->job->isClosed()) {
            return [false, 'This Job is already closed.'];
        }

        if ($jobExtra->job->isCompleted()) {
            return [false, 'Requesting extra materials for completed jobs is not allowed.'];
        }

        if ($jobExtra->isSubtracted()) {
            return [false, 'This Product is already subtracted from inventory.'];
        }

        $detail = $jobExtra->only(['product_id', 'quantity']);

        $detail['warehouse_id'] = $jobExtra->job->factory_id;

        $unavailableProducts = InventoryOperationService::unavailableProducts($detail);

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($jobExtra, $detail) {
            InventoryOperationService::subtract($detail);

            $jobExtra->subtract();
        });

        return [true, ''];
    }

    private function isQuantityValid($quantity, $available, $wip)
    {
        return $quantity >= ($available + $wip);
    }

    public function close($job)
    {
        if (!$job->isCompleted()) {
            return [false, 'This Job is not Completed yet.'];
        }

        if ($job->isClosed()) {
            return [false, 'This Job is already closed.'];
        }

        $job->close();

        return [true, ''];
    }
}
