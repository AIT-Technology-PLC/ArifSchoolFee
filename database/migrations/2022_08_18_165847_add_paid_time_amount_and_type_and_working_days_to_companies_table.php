<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->decimal('paid_time_off_amount', 22)->default(0)->after('can_show_branch_detail_on_print');
            $table->string('paid_time_off_type')->default('Days')->after('paid_time_off_amount');
            $table->bigInteger('working_days')->default(30)->after('paid_time_off_type');
        });
    }

    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('paid_time_off_amount');
            $table->dropColumn('paid_time_off_type');
            $table->dropColumn('working_days');
        });
    }
};