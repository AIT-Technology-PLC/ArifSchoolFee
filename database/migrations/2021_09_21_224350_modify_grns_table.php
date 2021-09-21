<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ModifyGrnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            Schema::table('grns', function (Blueprint $table) {
                $table->bigInteger('added_by')->nullable()->unsigned();

                $table->foreign('added_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            });

            DB::statement("update grns set added_by = approved_by where status = 'Added To Inventory'");

            Schema::table('grns', function (Blueprint $table) {
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
