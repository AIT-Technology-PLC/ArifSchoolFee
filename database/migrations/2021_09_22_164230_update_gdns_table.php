<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateGdnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            Schema::table('gdns', function (Blueprint $table) {
                $table->dropUnique(['code']);
            });

            DB::statement('update gdns set code = SUBSTR(code, POSITION("_" IN code) + 1)');

            Schema::table('gdns', function (Blueprint $table) {
                $table->bigInteger('code')->change();

                $table->unique(['company_id', 'warehouse_id', 'code']);
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
