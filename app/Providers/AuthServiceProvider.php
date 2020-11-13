<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Permission;
use App\Policies\CompanyPolicy;
use App\Policies\EmployeePolicy;
use App\Policies\PermissionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Employee::class => EmployeePolicy::class,
        Permission::class => PermissionPolicy::class,
        Company::class => CompanyPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
