<?php

namespace App\Services\Models;

use App\Models\Job;
use App\Services\Inventory\InventoryOperationService;
use Illuminate\Support\Facades\DB;

class JobService
{
    public function addToWorkInProcess($request, $job)
    {
        if (!$job->isApproved()) {
            return [false, 'This job is not approved yet.', ''];
        }

        DB::transaction(function () use ($request, $job) {
            $this->subtract($request, $job, 'wip');
        });

        return [true, ''];
    }

    public function addToAvailable($request, $job)
    {
        if (!$job->isApproved()) {
            return [false, 'This job is not approved yet.', ''];
        }

        DB::transaction(function () use ($request, $job) {
            $this->subtract($request, $job, 'available');
        });

        return [true, ''];
    }

    private function subtract($request, $job, $type)
    {
        for ($i = 0; $i < count($request->job); $i++) {
            $job->jobDetails[$i]->update($request->job[$i]);
            $details = $job->jobDetails[$i]->billOfMaterial->billOfMaterialDetails()->get(['product_id', 'quantity'])->toArray();
            $details = data_set($details, '*.warehouse_id', $job->factory_id);
            $quantity = $request->job[$i][$type];

            $details = collect($details)->transform(function ($detail) use ($quantity) {
                $detail['quantity'] = $detail['quantity'] * $quantity;
                return $detail;
            });
        }

        $unavailableProducts = InventoryOperationService::unavailableProducts($details);

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        $addDetails = [];

        for ($i = 0; $i < count($request->job); $i++) {

            $addDetails[] = [
                'product_id' => $request->job[$i]['product_id'],
                'quantity' => $request->job[$i][$type],
                'warehouse_id' => $job->factory_id,
            ];
        }

        InventoryOperationService::subtract($details);

        InventoryOperationService::add($addDetails, $type);
    }

    public function addExtra($jobExtra, $user)
    {
        if (!$user->hasWarehousePermission('add', $jobExtra->job->factory_id)) {
            return [false, 'You do not have permission to add to one or more of the warehouses.'];
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
}
