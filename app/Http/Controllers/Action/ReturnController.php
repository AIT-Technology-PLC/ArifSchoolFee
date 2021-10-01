<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Returnn;
use App\Traits\AddInventory;
use App\Traits\ApproveInventory;
use App\Traits\NotifiableUsers;

class ReturnController extends Controller
{
    use NotifiableUsers, AddInventory, ApproveInventory;

    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Return Management');

        $this->permission = 'Make Return';
    }

    public function printed(Returnn $return)
    {
        $this->authorize('view', $return);

        $return->load(['returnDetails.product', 'customer', 'company', 'createdBy', 'approvedBy']);

        return view('returns.print', compact('return'));
    }
}
