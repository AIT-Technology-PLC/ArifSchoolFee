<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gdn;

class GdnController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Gdn Management');
    }

    public function show(Gdn $gdn)
    {
        return $gdn->load(['gdnDetails', 'customer:id,company_name']);
    }
}
