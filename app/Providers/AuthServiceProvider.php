<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Gdn;
use App\Models\GeneralTenderChecklist;
use App\Models\Grn;
use App\Models\Merchandise;
use App\Models\Price;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Purchase;
use App\Models\PurchaseOrder;
use App\Models\Returnn;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\Tender;
use App\Models\TenderStatus;
use App\Models\Transfer;
use App\Models\Warehouse;
use App\Policies\CompanyPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\EmployeePolicy;
use App\Policies\GdnPolicy;
use App\Policies\GeneralTenderChecklistPolicy;
use App\Policies\GrnPolicy;
use App\Policies\MerchandisePolicy;
use App\Policies\PricePolicy;
use App\Policies\ProductCategoryPolicy;
use App\Policies\ProductPolicy;
use App\Policies\PurchaseOrderPolicy;
use App\Policies\PurchasePolicy;
use App\Policies\ReturnPolicy;
use App\Policies\SalePolicy;
use App\Policies\SupplierPolicy;
use App\Policies\TenderPolicy;
use App\Policies\TenderStatusPolicy;
use App\Policies\TransferPolicy;
use App\Policies\WarehousePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Returnn::class => ReturnPolicy::class,
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
