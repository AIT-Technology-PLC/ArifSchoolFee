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
        Schema::table('payrolls', function (Blueprint $table) {
            // $table->dropUnique(['starting_period', 'ending_period', 'warehouse_id']);
            // $table->dropIndex(['warehouse_id']);
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn('warehouse_id');

            $table->unique(['company_id', 'starting_period', 'ending_period']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
