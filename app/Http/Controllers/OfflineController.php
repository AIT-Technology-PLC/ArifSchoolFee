<?php

namespace App\Http\Controllers;

class OfflineController extends Controller
{
    public function offline()
    {
        return view('offline.offline');
    }
}
