<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->boolean('is_paid_time_off')->after('ending_period');
            $table->decimal('time_off_amount', 22)->after('is_paid_time_off');
        });
    }

    public function down()
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn('is_paid_time_off');
            $table->dropColumn('time_off_amount');
        });
    }
};