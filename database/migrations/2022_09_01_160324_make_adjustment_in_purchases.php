<?php

use App\Models\Company;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

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
            Purchase::query()
                ->where('type', 'Local Purchase')
                ->whereIn('company_id', Company::where('is_price_before_vat', 0)->pluck('id'))
                ->update([
                    'tax_type' => 'None',
                ]);

            Purchase::query()
                ->where('type', 'Local Purchase')
                ->whereIn('company_id', Company::where('is_price_before_vat', 1)->pluck('id'))
                ->update([
                    'tax_type' => 'VAT',
                ]);

            DB::table('purchases')
                ->whereNull('deleted_at')
                ->where('type', 'Local Purchase')
                ->where('payment_type', 'Cash Payment')
                ->update([
                    'cash_paid_type' => 'percent',
                    'cash_paid' => '100',
                ]);

            DB::table('purchases')
                ->whereNull('deleted_at')
                ->where('type', 'Local Purchase')
                ->where('payment_type', 'Credit Payment')
                ->update([
                    'cash_paid_type' => 'percent',
                    'cash_paid' => '0',
                ]);

            // Import
            Purchase::query()
                ->where('type', 'Import')
                ->update([
                    'currency' => 'USD',
                    'exchange_rate' => 0,
                    'freight_cost' => 0,
                    'freight_insurance_cost' => 0,
                ]);

            PurchaseDetail::query()
                ->whereRelation('purchase', 'type', 'Import')
                ->update([
                    'duty_rate' => 0,
                    'excise_tax' => 0,
                    'vat_rate' => 0,
                    'surtax' => 0,
                    'withholding_tax' => 0,
                    'amount' => 0,
                ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
