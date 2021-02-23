<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsApprovedToTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gdns', function (Blueprint $table) {
            $table->boolean('is_approved')->after('updated_by')->default(1);
        });

        Schema::table('grns', function (Blueprint $table) {
            $table->boolean('is_approved')->after('updated_by')->default(1);
        });

        Schema::table('transfers', function (Blueprint $table) {
            $table->boolean('is_approved')->after('updated_by')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gdns', function (Blueprint $table) {
            $table->dropColumn('is_approved');
        });

        Schema::table('grns', function (Blueprint $table) {
            $table->dropColumn('is_approved');
        });

        Schema::table('transfers', function (Blueprint $table) {
            $table->dropColumn('is_approved');
        });
    }
}
