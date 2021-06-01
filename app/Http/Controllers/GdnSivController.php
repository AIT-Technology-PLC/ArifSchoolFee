<?php

namespace App\Http\Controllers;

use App\Models\Gdn;
use Illuminate\Http\Request;

class GdnSivController extends Controller
{
    public function __invoke(Gdn $gdn, Request $request)
    {
        $this->authorize('view', $gdn);

        $request->merge([
            'siv' => $gdn->gdnDetails->toArray(),
        ]);

        return redirect()->route('sivs.create')->withInput($request->all());
    }
}
