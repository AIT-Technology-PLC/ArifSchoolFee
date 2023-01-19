<?php

use App\Models\MerchandiseBatch;
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
        Schema::table('grn_details', function (Blueprint $table) {
            $table->renameColumn('expiry_date', 'expires_on');
        });

        Schema::table('merchandise_batches', function (Blueprint $table) {
            $table->renameColumn('expiry_date', 'expires_on');
            $table->decimal('received_quantity', 22)->nullable();
        });

        MerchandiseBatch::all()->each(function ($merchandiseBatch) {
            $merchandiseBatch->received_quantity = $merchandiseBatch->quantity;
            $merchandiseBatch->save();
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
            $table->renameColumn('expires_on', 'expiry_date');
            $table->dropColumn('received_quantity');
        });
    }
};
