<?php

use App\Models\BillOfMaterial;
use App\Models\Company;
use App\Models\Credit;
use App\Models\Customer;
use App\Models\Gdn;
use App\Models\Grn;
use App\Models\Job;
use App\Models\Pad;
use App\Models\PadField;
use App\Models\Product;
use App\Models\ProformaInvoice;
use App\Models\Purchase;
use App\Models\Reservation;
use App\Models\Returnn;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\Tender;
use App\Models\TenderOpportunity;
use App\Models\Transaction;
use App\Models\TransactionField;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            $companies = Company::all();

            Customer::where('tin', '')->update(['tin' => null]);
            Supplier::where('tin', '')->update(['tin' => null]);

            foreach ($companies as $company) {
                $tins = Customer::where('company_id', $company->id)->whereRaw("(tin != null or tin != '')")->pluck('tin');

                foreach ($tins as $tin) {
                    $originalCustomer = Customer::where('company_id', $company->id)->where('tin', $tin)->oldest()->first();
                    $deleteCustomers = Customer::where('company_id', $company->id)->where('tin', $tin)->whereNot('id', $originalCustomer->id)->get();

                    Gdn::where('company_id', $company->id)->whereIn('customer_id', $deleteCustomers->pluck('id'))->update(['customer_id' => $originalCustomer->id]);
                    Sale::where('company_id', $company->id)->whereIn('customer_id', $deleteCustomers->pluck('id'))->update(['customer_id' => $originalCustomer->id]);
                    Tender::where('company_id', $company->id)->whereIn('customer_id', $deleteCustomers->pluck('id'))->update(['customer_id' => $originalCustomer->id]);
                    Reservation::where('company_id', $company->id)->whereIn('customer_id', $deleteCustomers->pluck('id'))->update(['customer_id' => $originalCustomer->id]);
                    TenderOpportunity::where('company_id', $company->id)->whereIn('customer_id', $deleteCustomers->pluck('id'))->update(['customer_id' => $originalCustomer->id]);
                    Returnn::where('company_id', $company->id)->whereIn('customer_id', $deleteCustomers->pluck('id'))->update(['customer_id' => $originalCustomer->id]);
                    ProformaInvoice::where('company_id', $company->id)->whereIn('customer_id', $deleteCustomers->pluck('id'))->update(['customer_id' => $originalCustomer->id]);
                    Credit::where('company_id', $company->id)->whereIn('customer_id', $deleteCustomers->pluck('id'))->update(['customer_id' => $originalCustomer->id]);
                    BillOfMaterial::where('company_id', $company->id)->whereIn('customer_id', $deleteCustomers->pluck('id'))->update(['customer_id' => $originalCustomer->id]);
                    Job::where('company_id', $company->id)->whereIn('customer_id', $deleteCustomers->pluck('id'))->update(['customer_id' => $originalCustomer->id]);
                    TransactionField::whereIn('transaction_id', Transaction::where('company_id', $company->id)->pluck('id'))->whereIn('pad_field_id', PadField::whereIn('pad_id', Pad::where('company_id', $company->id)->pluck('id'))->where('label', 'Customer')->pluck('id'))->whereIn('value', $deleteCustomers->pluck('id'))->update(['value' => $originalCustomer->id]);

                    Customer::where('company_id', $company->id)->whereIn('id', $deleteCustomers->pluck('id'))->forceDelete();
                }
            }

            foreach ($companies as $company) {
                $tins = Supplier::where('company_id', $company->id)->whereRaw("(tin != null or tin != '')")->pluck('tin');

                foreach ($tins as $tin) {
                    $originalSupplier = Supplier::where('company_id', $company->id)->where('tin', $tin)->oldest()->first();
                    $deleteSuppliers = Supplier::where('company_id', $company->id)->where('tin', $tin)->whereNot('id', $originalSupplier->id)->get();

                    Product::where('company_id', $company->id)->whereIn('supplier_id', $deleteSuppliers->pluck('id'))->update(['supplier_id' => $originalSupplier->id]);
                    Purchase::where('company_id', $company->id)->whereIn('supplier_id', $deleteSuppliers->pluck('id'))->update(['supplier_id' => $originalSupplier->id]);
                    Grn::where('company_id', $company->id)->whereIn('supplier_id', $deleteSuppliers->pluck('id'))->update(['supplier_id' => $originalSupplier->id]);
                    TransactionField::whereIn('transaction_id', Transaction::where('company_id', $company->id)->pluck('id'))->whereIn('pad_field_id', PadField::whereIn('pad_id', Pad::where('company_id', $company->id)->pluck('id'))->where('label', 'Supplier')->pluck('id'))->whereIn('value', $deleteSuppliers->pluck('id'))->update(['value' => $originalSupplier->id]);

                    Supplier::where('company_id', $company->id)->whereIn('id', $deleteSuppliers->pluck('id'))->forceDelete();
                }
            }
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->unique(['company_id', 'tin']);
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->unique(['company_id', 'tin']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique(['company_id', 'tin']);
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropUnique(['company_id', 'tin']);
        });
    }
};
