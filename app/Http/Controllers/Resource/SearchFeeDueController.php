<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SearchFeeDueController extends Controller
{
    public function index()
    {
        abort_if(authUser()->cannot('Search Fee Due'), 403);
    }
}
