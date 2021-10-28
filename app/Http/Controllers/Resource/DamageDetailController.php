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

        if ($damageDetail->damage->isSubtracted()) {
            abort(403);
        }

        if ($damageDetail->damage->isApproved() && !auth()->user()->can('Delete Approved Damage')) {
            abort(403);
        }

        $damageDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
