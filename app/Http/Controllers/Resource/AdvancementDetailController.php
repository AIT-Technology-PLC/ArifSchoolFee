<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\AdvancementDetail;

class AdvancementDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Advancement Management');
    }

    public function destroy(AdvancementDetail $advancementDetail)
    {
        $this->authorize('delete', $advancementDetail->advancement);

        if ($advancementDetail->advancement->isApproved()) {
            return back()->with('failedMessage', 'You can not delete an advancement that is approved.');
        }

        $advancementDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
