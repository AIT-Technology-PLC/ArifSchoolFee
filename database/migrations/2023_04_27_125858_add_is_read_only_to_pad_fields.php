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
        Schema::table('pad_fields', function (Blueprint $table) {
            $table->boolean('is_readonly')->after('is_printable')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pad_fields', function (Blueprint $table) {
            $table->dropColumn('is_readonly');
        });
    }
};
