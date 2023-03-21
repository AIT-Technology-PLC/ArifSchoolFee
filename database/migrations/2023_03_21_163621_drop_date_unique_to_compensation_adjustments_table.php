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
        Schema::table('compensation_adjustments', function (Blueprint $table) {
            $table->dropUnique(['company_id', 'starting_period']);
            $table->dropUnique(['company_id', 'ending_period']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compensation_adjustments', function (Blueprint $table) {
            $table->unique(['company_id', 'starting_period']);
            $table->unique(['company_id', 'ending_period']);
        });
    }
};