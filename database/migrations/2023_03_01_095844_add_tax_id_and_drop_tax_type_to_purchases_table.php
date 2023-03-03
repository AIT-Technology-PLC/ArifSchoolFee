<?php

use App\Models\Purchase;
use App\Models\Tax;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->foreignId('tax_id')->nullable()->after('supplier_id')->constrained()->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $purchases = Purchase::where('type', 'Local Purchase')->get();
            foreach ($purchases as $purchase) {
                if ($purchase->tax_type == null || $purchase->tax_type == 'None') {
                    $taxType = Tax::where('company_id', $purchase->company_id)->where('type', 'NONE')->first();
                    $purchase->tax_id = $taxType->id;
                }

                if ($purchase->tax_type == 'VAT') {
                    $taxType = Tax::where('company_id', $purchase->company_id)->where('type', 'VAT')->first();
                    $purchase->tax_id = $taxType->id;
                }

                if ($purchase->tax_type == 'TOT') {
                    $taxType = Tax::where('company_id', $purchase->company_id)->where('type', 'TOT2')->first();
                    $purchase->tax_id = $taxType->id;
                }

                $purchase->save();
            }

            $table->dropColumn('tax_type');
        });
    }

    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign('tax_id');
            $table->dropColumn('tax_id');
            $table->string('tax_type');
        });
    }
};