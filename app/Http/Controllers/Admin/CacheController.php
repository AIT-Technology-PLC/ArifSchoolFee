<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class CacheController extends Controller
{
    public function index()
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        return view('admin.caches.index');
    }

    // Clear view cache
    public function clearViewCache()
    {
        Artisan::call('view:clear');
        return redirect()->back()->with('successMessage', 'View Cache Cleared!');
    }

    // Clear route cache
    public function clearRouteCache()
    {
        Artisan::call('route:clear');
        return redirect()->back()->with('successMessage', 'Route Cache Cleared!');
    }

    // Clear config cache
    public function clearConfigCache()
    {
        Artisan::call('config:clear');
        return redirect()->back()->with('successMessage', 'Config Cache Cleared!');
    }

    // Clear all caches (application and database cache)
    public function clearCache()
    {
        Artisan::call('cache:clear');
        return redirect()->back()->with('successMessage', 'All Caches Cleared!');
    }
}
