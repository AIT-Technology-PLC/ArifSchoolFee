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
            $table->boolean('is_payroll_basic_salary_after_absence_deduction')->default(0);
            $table->boolean('does_payroll_basic_salary_include_overtime')->default(0);
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
            $table->dropColumn('is_payroll_basic_salary_after_absence_deduction');
            $table->dropColumn('does_payroll_basic_salary_include_overtime');
        });
    }
};
