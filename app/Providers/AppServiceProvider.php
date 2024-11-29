<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        require_once __DIR__ . '/../Helpers/Global.php';
    }

    public function boot()
    {
        Paginator::defaultSimpleView('pagination::simple-default');
    }
}
