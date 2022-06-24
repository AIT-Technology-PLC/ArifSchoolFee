<?php

namespace App\Models;

use App\Models\BillOfMaterial;
use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobDetail extends Model
{
    use SoftDeletes, TouchParentUserstamp;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function billOfMaterial()
    {
        return $this->belongsTo(BillOfMaterial::class);
    }

    public function scopeWip($query)
    {
        return $query->where('wip', '>', 0);
    }

    public function scopeAvailable($query)
    {
        return $query->where('available', '>', 0);
    }

    public function parentModel()
    {
        return $this->job;
    }

    public function isCompleted()
    {
        return $this->quantity == $this->available;
    }

    public function canAddToWip()
    {
        return $this->quantity > $this->wip + $this->available;
    }

    public function getCompletionRateAttribute()
    {
        $availableQuantity = $this->available;

        $wipQuantity = $this->wip * 0.5;

        $totalQuantity = $this->quantity ?? 0.00;

        if (!$totalQuantity) {
            return 100.00;
        }

        $completionRate = ($availableQuantity + $wipQuantity) / $totalQuantity;

        if ($completionRate == 1) {
            return 100.00;
        }

        return number_format($completionRate * 100, 2);
    }

    private function historyQuery($warehouse)
    {
        return $this
            ->with('billOfMaterial.billOfMaterialDetails')
            ->whereIn('job_order_id', function ($query) use ($warehouse) {
                $query->select('id')
                    ->from('job_orders')
                    ->where('company_id', userCompany()->id)
                    ->where('factory_id', $warehouse->id);
            });
    }

    public function getByWarehouseAndProduct($warehouse, $product)
    {
        $jobDetailsBillOfMaterials = $this
            ->historyQuery($warehouse)
            ->where(function ($query) {
                $query->where('available', '>', 0)
                    ->orWhere('wip', '>', 0);
            })
            ->get()
            ->map(function ($jobDetail) use ($product) {
                return $jobDetail
                    ->billOfMaterial
                    ->billOfMaterialDetails
                    ->where('product_id', $product->id)
                    ->map(function ($billOfMaterialDetail) use ($jobDetail) {
                        return (new JobDetail([
                            'job_order_id' => $jobDetail->job_order_id,
                            'available' => number_format($billOfMaterialDetail->quantity * $jobDetail->available, 2),
                            'wip' => number_format($billOfMaterialDetail->quantity * $jobDetail->wip, 2),
                        ]))->setAttribute('is_bill_of_material', 1)->load('job');
                    });
            })
            ->flatten(1);

        $jobDetails = $this
            ->historyQuery($warehouse)
            ->where('product_id', $product->id)
            ->where('available', '>', 0)
            ->get()
            ->load([
                'job' => function ($query) {
                    return $query->withoutGlobalScopes([BranchScope::class]);
                }]
            );

        $jobDetailsBillOfMaterials->push(...$jobDetails);

        return $jobDetailsBillOfMaterials;
    }
}
