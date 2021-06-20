<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveExecutedByFromSivs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sivs', function (Blueprint $table) {
            $table->dropForeign(['executed_by']);
            $table->dropColumn(['executed_by']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sivs', function (Blueprint $table) {
            $table->bigInteger('executed_by')->nullable()->unsigned()->after('approved_by');
            $table->foreign('executed_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });
    }
}
