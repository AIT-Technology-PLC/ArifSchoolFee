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
            $table->string('print_orientation')->default('portrait');
            $table->string('print_paper_size')->default('A4');
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
            $table->dropColumn(['print_orientation', 'print_paper_size']);
        });
    }
};
