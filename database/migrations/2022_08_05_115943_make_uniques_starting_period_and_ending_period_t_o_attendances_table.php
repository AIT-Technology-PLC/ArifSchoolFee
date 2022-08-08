<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->date('starting_period')->change();
            $table->date('ending_period')->change();

            $table->unique(['starting_period', 'warehouse_id']);
            $table->unique(['ending_period', 'warehouse_id']);
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dateTime('starting_period')->change();
            $table->dateTime('ending_period')->change();
            $table->dropUnique(['starting_period', 'warehouse_id']);
            $table->dropUnique(['ending_period', 'warehouse_id']);
        });
    }
};
