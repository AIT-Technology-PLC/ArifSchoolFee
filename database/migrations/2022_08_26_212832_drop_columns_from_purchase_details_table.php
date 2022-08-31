<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->dropColumn('freight_cost');
            $table->dropColumn('freight_insurance_cost');
        });
    }

    public function down()
    {
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->decimal('freight_cost', 22)->nullable();
            $table->decimal('freight_insurance_cost', 22)->nullable();
        });
    }
};
