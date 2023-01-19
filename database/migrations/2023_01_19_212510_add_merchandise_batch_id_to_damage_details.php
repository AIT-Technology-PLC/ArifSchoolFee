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
        Schema::table('merchandise_batches', function (Blueprint $table) {
            $table->dropForeign(['damage_id']);
            $table->dropColumn('damage_id');
        });

        Schema::table('damage_details', function (Blueprint $table) {
            $table->foreignId('merchandise_batch_id')->nullable()->after('warehouse_id')->constrained()->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchandise_batches', function (Blueprint $table) {
            $table->foreignId('damage_id')->nullable()->after('merchandise_id')->constrained()->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('damage_details', function (Blueprint $table) {
            $table->dropForeign(['merchandise_batch_id']);
            $table->dropColumn('merchandise_batch_id');
        });
    }
};
