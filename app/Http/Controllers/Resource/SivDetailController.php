<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\SivDetail;

class SivDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Siv Management');
    }

    public function destroy(SivDetail $sivDetail)
    {
        $this->authorize('delete', $sivDetail->siv);

        if ($sivDetail->siv->isApproved()) {
            abort(403);
        }

        $sivDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
