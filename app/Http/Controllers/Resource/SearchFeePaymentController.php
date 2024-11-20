<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;

class SearchFeePaymentController extends Controller
{
    public function index()
    {
        abort_if(authUser()->cannot('Search Fee Payment'), 403);
    }
}
