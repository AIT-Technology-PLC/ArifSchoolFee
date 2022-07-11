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
        Schema::table('employees', function (Blueprint $table) {
            $table->string('gender');
            $table->string('address');
            $table->string('bank_name');
            $table->string('bank_account');
            $table->string('tin_number');
            $table->string('job_type');
            $table->string('phone');
            $table->string('id_type');
            $table->string('id_number');
            $table->dateTime('date_of_hiring');
            $table->decimal('gross_salary', 22);
            $table->dateTime('date_of_birth');
            $table->string('emergency_name');
            $table->string('emergency_phone');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('gender');
            $table->dropColumn('address');
            $table->dropColumn('bank_name');
            $table->dropColumn('bank_account');
            $table->dropColumn('tin_number');
            $table->dropColumn('job_type');
            $table->dropColumn('phone');
            $table->dropColumn('id_type');
            $table->dropColumn('id_number');
            $table->dropColumn('date_of_hiring');
            $table->dropColumn('gross_salary', 22);
            $table->dropColumn('date_of_birth');
            $table->dropColumn('emergency_name');
            $table->dropColumn('emergency_phone');
            $table->dropConstrainedForeignId('department_id');
        });
    }
};