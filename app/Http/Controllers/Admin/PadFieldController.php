<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PadField;

class PadFieldController extends Controller
{
    public function destroy(PadField $padField)
    {
        abort_if($padField->pad->isEnabled(), 403);

        $padField->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
