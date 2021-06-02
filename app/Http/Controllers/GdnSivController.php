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
            'purpose' => 'DO',
            'ref_num' => $gdn->code,
            'siv' => $gdn->gdnDetails->toArray(),
        ]);

        return redirect()->route('sivs.create')->withInput($request->all());
    }
}
