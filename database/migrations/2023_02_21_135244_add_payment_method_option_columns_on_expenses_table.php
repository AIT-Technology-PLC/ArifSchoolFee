<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->string('payment_type')->after('reference_number');
            $table->string('bank_name')->nullable()->after('payment_type');
            $table->string('bank_reference_number')->nullable()->after('bank_name');
        });
    }

    public function down()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('payment_type');
            $table->dropColumn('bank_name');
            $table->dropColumn('bank_reference_number');
        });
    }
};
