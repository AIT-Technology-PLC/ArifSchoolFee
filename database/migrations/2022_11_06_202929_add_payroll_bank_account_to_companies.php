<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('companies', function (Blueprint $table) {
            $table->after('income_tax_region', function () use ($table) {
                $table->string('payroll_bank_name')->nullable();
                $table->string('payroll_bank_account_number')->nullable();
            });
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn('bank_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['payroll_bank_name', 'payroll_bank_account_number']);
        });
    }
};
