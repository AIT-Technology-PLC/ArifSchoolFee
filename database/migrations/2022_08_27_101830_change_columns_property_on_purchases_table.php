<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('cash_paid_type')->nullable()->change();
            $table->decimal('cash_paid', 22)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('cash_paid_type');
            $table->decimal('cash_paid', 22);
        });
    }
};
