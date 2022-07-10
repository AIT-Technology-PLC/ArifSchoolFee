<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\DamageDetail;

class DamageDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Damage Management');
    }

    public function destroy(DamageDetail $damageDetail)
    {
        $this->authorize('delete', $damageDetail->damage);

        abort_if($damageDetail->damage->isSubtracted(), 403);

        abort_if($damageDetail->damage->isApproved() && ! authUser()->can('Delete Approved Damage'), 403);

        $damageDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
