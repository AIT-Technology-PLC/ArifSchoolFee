<?php

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Database\Migrations\Migration;

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
            $table->after('income_tax_region', function ($table) {
                $table->string('payroll_bank_name')->nullable();
                $table->string('payroll_bank_account_number')->nullable();
            });
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn('bank_name');
        });

        Company::query()->update(['paid_time_off_amount' => 16, 'paid_time_off_type', 'Days']);
        Employee::query()->update(['paid_time_off_amount' => 16]);
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
