<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditFeaturablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            DELETE
            FROM featurables
            WHERE featurables.feature_id NOT IN (SELECT id FROM features)
        ');

        Schema::table('featurables', function (Blueprint $table) {
            $table->foreign('feature_id')
                ->references('id')
                ->on('features')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        DB::statement('
            DELETE
            FROM limitables
            WHERE limitables.limit_id NOT IN (SELECT id FROM limits)
        ');

        Schema::table('limitables', function (Blueprint $table) {
            $table->foreign('limit_id')
                ->references('id')
                ->on('limits')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
}
