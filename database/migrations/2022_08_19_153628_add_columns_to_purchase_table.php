<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('cash_paid_type')->after('payment_type');
            $table->decimal('cash_paid', 22)->after('cash_paid_type');
            $table->dateTime('due_date')->nullable()->after('cash_paid');
        });
    }

    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('cash_paid_type');
            $table->dropColumn('cash_paid');
            $table->dropColumn('due_date');
        });
    }
};
