<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTransferTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->bigInteger('subtracted_by')->nullable()->unsigned()->after('approved_by');
            $table->bigInteger('added_by')->nullable()->unsigned()->after('subtracted_by');
            $table->bigInteger('transferred_from')->nullable()->unsigned()->after('added_by');
            $table->bigInteger('transferred_to')->nullable()->unsigned()->after('transferred_from');

            $table->foreign('subtracted_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('added_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('transferred_from')->references('id')->on('warehouses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('transferred_to')->references('id')->on('warehouses')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->dropForeign(['subtracted_by']);
            $table->dropForeign(['added_by']);
            $table->dropForeign(['transferred_from']);
            $table->dropForeign(['transferred_to']);

            $table->dropColumn([
                'subtracted_by',
                'added_by',
                'transferred_from',
                'transferred_to',
            ]);
        });
    }
}
