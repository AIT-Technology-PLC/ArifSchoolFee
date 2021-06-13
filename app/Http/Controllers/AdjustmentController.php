<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdjustmentRequest;
use App\Models\Adjustment;
use App\Models\Product;
use App\Models\Warehouse;
use App\Notifications\AdjustmentPrepared;
use App\Traits\ApproveInventory;
use App\Traits\NotifiableUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AdjustmentController extends Controller
{
    use NotifiableUsers, ApproveInventory;

    private $permission;

    public function __construct()
    {
        $this->authorizeResource(Adjustment::class, 'adjustment');

        $this->permission = 'Make Adjustment';
    }

    public function index()
    {
        $adjustments = Adjustment::with(['createdBy', 'updatedBy', 'approvedBy', 'adjustedBy'])->get();

        $totalAdjustments = $adjustments->count();

        $totalNotApproved = $adjustments->whereNull('approved_by')->count();

        $totalNotAdjusted = $adjustments->whereNotNull('approved_by')->whereNull('adjusted_by')->count();

        $totalAdjusted = $adjustments->whereNotNull('adjusted_by')->count();

        return view('adjustments.index', compact('adjustments', 'totalAdjustments', 'totalNotApproved', 'totalNotAdjusted', 'totalAdjusted'));
    }

    public function create(Product $product, Warehouse $warehouse)
    {
        $products = $product->getProductNames();

        $warehouses = $warehouse->getAllWithoutRelations();

        $currentAdjustmentCode = (Adjustment::select('code')->companyAdjustment()->latest()->first()->code) ?? 0;

        return view('adjustments.create', compact('products', 'warehouses', 'currentAdjustmentCode'));
    }

    public function store(StoreAdjustmentRequest $request)
    {
        $adjustment = DB::transaction(function () use ($request) {
            $adjustment = Adjustment::create($request->except('adjustment'));

            $adjustment->adjustmentDetails()->createMany($request->adjustment);

            Notification::send($this->notifiableUsers('Approve Adjustment'), new AdjustmentPrepared($adjustment));

            return $adjustment;
        });

        return redirect()->route('adjustments.show', $adjustment->id);
    }

    public function show(Adjustment $adjustment)
    {
        $adjustment->load(['adjustmentDetails.warehouse', 'adjustmentDetails.product']);

        return view('adjustments.show', compact('adjustment'));
    }
}
