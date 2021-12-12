<?php

use App\Models\Tender;
use App\Models\TenderLotDetail;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenderLots extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tender_lots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();

            $table->index('tender_id');
        });

        Tender::all()->each->tenderLots()->create();

        Schema::table('tender_details', function (Blueprint $table) {
            $table->rename('tender_lot_details');

            $table->foreignId('tender_lot_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        TenderLotDetail::all()->each(function ($tenderLotDetail) {
            $tenderLotDetail->tender_lot_id = $tenderLotDetail->tender->tenderLots->first()->id;

            $tenderLotDetail->save();
        });

        Schema::table('tender_lot_details', function (Blueprint $table) {
            $table->dropForeign('tender_id');
            $table->dropColumn(['tender_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tender_lot_details', function (Blueprint $table) {
            $table->foreignId('tender_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        TenderLotDetail::all()->each(function ($tenderLotDetail) {
            $tenderLotDetail->tender_id = $tenderLotDetail->tenderLot->tender_id;

            $tenderLotDetail->save();
        });

        Schema::table('tender_lot_details', function (Blueprint $table) {
            $table->rename('tender_details');

            $table->dropForeign('tender_lot_id');
            $table->dropColumn(['tender_lot_id']);
        });

        Schema::drop('tender_lots');
    }
}
