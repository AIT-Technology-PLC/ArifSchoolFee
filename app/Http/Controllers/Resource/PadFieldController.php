<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\PadField;

class PadFieldController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Pad Management');
    }

    public function destroy(PadField $padField)
    {
        $this->authorize('delete', $padField->pad);

        abort_if($padField->pad->isEnabled(), 403);

        $padField->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
