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
        Schema::table('pads', function (Blueprint $table) {
            $table->string('convert_to')->nullable()->after('module');
            $table->string('convert_from')->nullable()->after('convert_to');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pads', function (Blueprint $table) {
            $table->dropColumn('convert_from', 'convert_to');
        });
    }
};
