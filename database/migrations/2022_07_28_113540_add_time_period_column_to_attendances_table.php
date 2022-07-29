<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->renameColumn('date', 'issued_on');
            $table->dateTime('starting_period')->nullable()->after('date');
            $table->dateTime('ending_period')->nullable()->after('starting_period');
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->renameColumn('issued_on', 'date');
            $table->dropColumn('starting_period');
            $table->dropColumn('ending_period');
        });
    }
};