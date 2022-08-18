<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('advancement_details', function (Blueprint $table) {
            $table->foreignId('compensation_id')->after('employee_id')->nullable()->constrained('compensations')->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('amount', 22)->after('compensation_id');
            $table->dropColumn('gross_salary');
        });
    }

    public function down()
    {
        Schema::table('advancement_details', function (Blueprint $table) {
            $table->dropConstrainedForeignId('compensation_id');
            $table->dropColumn('amount');
            $table->decimal('gross_salary', 22)->nullable()->after('employee_id');
        });
    }
};