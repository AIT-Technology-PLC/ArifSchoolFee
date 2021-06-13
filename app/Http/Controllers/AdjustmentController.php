<?php

namespace App\Http\Controllers;

use App\Models\Adjustment;
use App\Traits\ApproveInventory;
use App\Traits\NotifiableUsers;

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
}
