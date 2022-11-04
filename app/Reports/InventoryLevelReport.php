<?php

namespace App\Reports;

use App\Models\AdjustmentDetail;
use App\Models\DamageDetail;
use App\Models\GdnDetail;
use App\Models\GrnDetail;
use App\Models\JobDetailHistory;
use App\Models\JobExtra;
use App\Models\Merchandise;
use App\Models\ReservationDetail;
use App\Models\ReturnDetail;
use App\Models\TransferDetail;
use Illuminate\Support\Arr;

class InventoryLevelReport
{
    private $query;

    private $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;

        $this->setQuery();
    }

    public function __get($name)
    {
        if (!isset($this->$name)) {
            $this->$name = $this->$name();
        }

        return $this->$name;
    }

    private function setQuery()
    {
        $availableMerchandises = Merchandise::query()
            ->join('products', 'merchandises.product_id', '=', 'products.id')
            ->join('product_categories', 'products.product_category_id', '=', 'product_categories.id')
            ->join('warehouses', 'merchandises.warehouse_id', '=', 'warehouses.id')
            ->where('merchandises.available', '>', 0)
            ->whereIn('warehouses.id', authUser()->getAllowedWarehouses('read')->pluck('id'))
            ->where('products.type', '!=', 'Services')
            ->select([
                'merchandises.available as available',
                'products.id as product_id',
                'products.name as product',
                'products.type as type',
                'products.unit_of_measurement as unit',
                'product_categories.name as category',
                'warehouses.id as warehouse_id',
                'warehouses.name as warehouse',
            ])->get();

        $availableMerchandiseData = collect();

        foreach ($availableMerchandises as $merchandiseDetail) {
            $currentMerchandiseItem = [
                'product' => $merchandiseDetail->product,
                'type' => $merchandiseDetail->type,
                'category' => $merchandiseDetail->category,
                'warehouse' => $merchandiseDetail->warehouse,
                'unit' => $merchandiseDetail->unit,
                'available' => $merchandiseDetail->available,
            ];

            if (isset($this->filters['date'])) {
                foreach ($this->getGdnDetail as $gdnDetail) {
                    if (($merchandiseDetail->warehouse_id == $gdnDetail->warehouse_id) && ($merchandiseDetail->product_id == $gdnDetail->product_id)) {
                        $currentMerchandiseItem['available'] = $merchandiseDetail->available + $gdnDetail->quantity;
                    }
                }

                foreach ($this->getGrnDetail as $grnDetail) {
                    if (($merchandiseDetail->warehouse_id == $grnDetail->warehouse_id) && ($merchandiseDetail->product_id == $grnDetail->product_id)) {
                        $currentMerchandiseItem['available'] = $currentMerchandiseItem['available'] - $grnDetail->quantity;
                    }
                }

                foreach ($this->getTransferDetail as $transferDetail) {
                    if (($transferDetail->transfer->isSubtracted()) && ($merchandiseDetail->warehouse_id == $transferDetail->transferred_from) && ($merchandiseDetail->product_id == $transferDetail->product_id)) {
                        $currentMerchandiseItem['available'] = $currentMerchandiseItem['available'] + $transferDetail->quantity;
                    }

                    if (($transferDetail->transfer->isAdded()) && ($merchandiseDetail->warehouse_id == $transferDetail->transferred_to) && ($merchandiseDetail->product_id == $transferDetail->product_id)) {
                        $currentMerchandiseItem['available'] = $currentMerchandiseItem['available'] - $transferDetail->quantity;
                    }
                }

                foreach ($this->getAdjustmentDetail as $adjustmentDetail) {
                    if (($merchandiseDetail->warehouse_id == $adjustmentDetail->warehouse_id) && ($merchandiseDetail->product_id == $adjustmentDetail->product_id)) {
                        if ($adjustmentDetail->is_subtract == 1) {
                            $currentMerchandiseItem['available'] = $currentMerchandiseItem['available'] + $adjustmentDetail->quantity;
                        }

                        if ($adjustmentDetail->is_subtract == 0) {
                            $currentMerchandiseItem['available'] = $currentMerchandiseItem['available'] - $adjustmentDetail->quantity;
                        }
                    }
                }

                foreach ($this->getDamageDetail as $damageDetail) {
                    if (($merchandiseDetail->warehouse_id == $damageDetail->warehouse_id) && ($merchandiseDetail->product_id == $damageDetail->product_id)) {
                        $currentMerchandiseItem['available'] = $currentMerchandiseItem['available'] + $damageDetail->quantity;
                    }
                }

                foreach ($this->getReturnDetail as $returnDetail) {
                    if (($merchandiseDetail->warehouse_id == $returnDetail->warehouse_id) && ($merchandiseDetail->product_id == $returnDetail->product_id)) {
                        $currentMerchandiseItem['available'] = $currentMerchandiseItem['available'] - $returnDetail->quantity;
                    }
                }

                foreach ($this->getReservationDetail as $reservationDetail) {
                    if (($merchandiseDetail->warehouse_id == $reservationDetail->warehouse_id) && ($merchandiseDetail->product_id == $reservationDetail->product_id)) {
                        $currentMerchandiseItem['available'] = $currentMerchandiseItem['available'] + $reservationDetail->quantity;
                    }
                }

                foreach ($this->getJobExtraDetail as $jobExtraDetail) {
                    if (($merchandiseDetail->warehouse_id == $jobExtraDetail->factory_id) && ($merchandiseDetail->product_id == $jobExtraDetail->product_id)) {
                        if ($jobExtraDetail->status == "added") {
                            $currentMerchandiseItem['available'] = $currentMerchandiseItem['available'] - $jobExtraDetail->quantity;
                        }

                        if ($jobExtraDetail->status == "subtracted") {
                            $currentMerchandiseItem['available'] = $currentMerchandiseItem['available'] + $jobExtraDetail->quantity;
                        }
                    }
                }

                foreach ($this->getJobDetailHistory as $jobDetailHistory) {
                    if (($merchandiseDetail->warehouse_id == $jobDetailHistory->jobDetail->job->factory_id) && ($merchandiseDetail->product_id == $jobDetailHistory->product_id)) {
                        if ($jobDetailHistory->type == "added") {
                            $currentMerchandiseItem['available'] = $currentMerchandiseItem['available'] - $jobDetailHistory->quantity;
                        }

                        if ($jobDetailHistory->type == "subtracted") {
                            $currentMerchandiseItem['available'] = $currentMerchandiseItem['available'] + $jobDetailHistory->quantity;
                        }
                    }
                }

                if ($currentMerchandiseItem['available'] < 0) {
                    $currentMerchandiseItem['available'] = 0;
                }
            }

            $availableMerchandiseData->push($currentMerchandiseItem);
        }

        $this->query = $availableMerchandiseData;
    }

    public function getInventoryLevels()
    {
        $inventoryReport = (clone $this->query)->groupBy('product')->map->keyBy('warehouse');

        $organizedAvailableMerchandise = collect();

        foreach ($inventoryReport as $merchandiseKey => $merchandiseValue) {
            $currentMerchandiseItem = [
                'product' => $merchandiseValue->first()['product'],
                'type' => $merchandiseValue->first()['type'],
                'category' => $merchandiseValue->first()['category'],
                'unit' => $merchandiseValue->first()['unit'],
                'total_balance' => $merchandiseValue->sum('available'),
            ];

            foreach ($merchandiseValue as $key => $value) {
                $currentMerchandiseItem = Arr::add($currentMerchandiseItem, $key, $value['available']);
            }

            $organizedAvailableMerchandise->push($currentMerchandiseItem);
        }

        return $organizedAvailableMerchandise;
    }

    public function getGdnDetail()
    {
        return GdnDetail::query()
            ->whereHas('gdn', function ($q) {
                return $q->subtracted()->withoutGlobalScopes([BranchScope::class])
                    ->when(isset($this->filters['date']), fn($q) => $q->whereDate('issued_on', '>', $this->filters['date']));
            })->get();
    }

    public function getGrnDetail()
    {
        return GrnDetail::query()
            ->whereHas('grn', function ($q) {
                return $q->added()->withoutGlobalScopes([BranchScope::class])
                    ->when(isset($this->filters['date']), fn($q) => $q->whereDate('issued_on', '>', $this->filters['date']));
            })->get();
    }

    public function getTransferDetail()
    {
        return TransferDetail::query()
            ->whereHas('transfer', function ($q) {
                return $q->approved()->withoutGlobalScopes([BranchScope::class])
                    ->when(isset($this->filters['date']), fn($q) => $q->whereDate('issued_on', '>', $this->filters['date']));
            })->get();
    }

    public function getDamageDetail()
    {
        return DamageDetail::query()
            ->whereHas('damage', function ($q) {
                return $q->subtracted()->withoutGlobalScopes([BranchScope::class])
                    ->when(isset($this->filters['date']), fn($q) => $q->whereDate('issued_on', '>', $this->filters['date']));
            })->get();
    }

    public function getReturnDetail()
    {
        return ReturnDetail::query()
            ->whereHas('returnn', function ($q) {
                return $q->added()->withoutGlobalScopes([BranchScope::class])
                    ->when(isset($this->filters['date']), fn($q) => $q->whereDate('issued_on', '>', $this->filters['date']));
            })->get();
    }

    public function getAdjustmentDetail()
    {
        return AdjustmentDetail::query()
            ->whereHas('adjustment', function ($q) {
                return $q->adjusted()->withoutGlobalScopes([BranchScope::class])
                    ->when(isset($this->filters['date']), fn($q) => $q->whereDate('issued_on', '>', $this->filters['date']));
            })->get();
    }

    public function getReservationDetail()
    {
        return ReservationDetail::query()
            ->whereHas('reservation', function ($q) {
                return $q->reserved()->withoutGlobalScopes([BranchScope::class])
                    ->when(isset($this->filters['date']), fn($q) => $q->whereDate('issued_on', '>', $this->filters['date']));
            })->get();
    }

    public function getJobExtraDetail()
    {
        return JobExtra::query()
            ->whereHas('job', function ($q) {
                return $q->approved()->withoutGlobalScopes([BranchScope::class])
                    ->when(isset($this->filters['date']), fn($q) => $q->whereDate('issued_on', '>', $this->filters['date']));
            })->get();
    }

    public function getJobDetailHistory()
    {
        return JobDetailHistory::query()
            ->whereHas('jobDetail', function ($q) {
                return $q->withoutGlobalScopes([BranchScope::class])
                    ->when(isset($this->filters['date']), fn($q) => $q->whereDate('created_at', '>', $this->filters['date']));
            })->get();
    }
}