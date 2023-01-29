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
        Schema::table('compensations', function (Blueprint $table) {
            $table->boolean('has_formula')->after('maximum_amount')->default(0);
        });

        Schema::table('compensation_adjustment_details', function (Blueprint $table) {
            $table->json('options')->after('amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compensations', function (Blueprint $table) {
            $table->dropColumn('has_formula');
        });

        Schema::table('compensation_adjustment_details', function (Blueprint $table) {
            $table->dropColumn('options');
        });
    }
};
