<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyDamagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            Schema::table('damages', function (Blueprint $table) {
                $table->bigInteger('subtracted_by')->nullable()->unsigned();

                $table->foreign('subtracted_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            });

            DB::statement("update damages set subtracted_by = approved_by where status = 'Subtracted From Inventory'");

            Schema::table('damages', function (Blueprint $table) {
                $table->dropColumn(['status']);
            });
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
