<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnsFromMerchandises extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchandises', function (Blueprint $table) {
            $table->renameColumn('total_on_hand', 'on_hand');

            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);

            $table->dropColumn([
                'created_by',
                'updated_by',
                'total_received',
                'total_sold',
                'total_transfer',
                'total_broken',
                'total_returns',
                'received_on',
                'expires_on',
                'description',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchandises', function (Blueprint $table) {
            $table->renameColumn('on_hand', 'total_on_hand');
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->decimal('total_received', 22);
            $table->decimal('total_sold', 22);
            $table->decimal('total_transfer', 22);
            $table->decimal('total_broken', 22);
            $table->decimal('total_returns', 22);
            $table->dateTime('received_on')->nullable();
            $table->dateTime('expires_on')->nullable();
            $table->longText('description')->nullable();
        });
    }
}
