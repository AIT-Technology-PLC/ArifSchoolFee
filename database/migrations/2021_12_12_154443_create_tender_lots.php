<?php

use App\Models\Tender;
use App\Models\TenderLot;
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
        });

        Tender::all()->each(fn($tender) => $tender->tenderLots()->create());

        Schema::rename('tender_details', 'tender_lot_details');

        Schema::table('tender_lot_details', function (Blueprint $table) {
            $table->foreignId('tender_lot_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        TenderLotDetail::all()->each(function ($tenderLotDetail) {
            $tenderLotDetail->tender_lot_id = TenderLot::firstWhere('tender_id', $tenderLotDetail->tender_id)->id;
            $tenderLotDetail->save();
        });

        Schema::table('tender_lot_details', function (Blueprint $table) {
            $table->renameIndex('tender_details_product_id_index', 'tender_lot_details_product_id_index');

            $table->dropConstrainedForeignId('tender_id');
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
            $table->renameIndex('tender_lot_details_product_id_index', 'tender_details_product_id_index');

            $table->foreignId('tender_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->renameIndex('tender_lot_details_tender_id_foreign', 'tender_details_tender_id_foreign');
        });

        TenderLotDetail::all()->each(function ($tenderLotDetail) {
            $tenderLotDetail->tender_id = $tenderLotDetail->tenderLot->tender_id;

            $tenderLotDetail->save();
        });

        Schema::table('tender_lot_details', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tender_lot_id');
        });

        Schema::rename('tender_lot_details', 'tender_details');

        Schema::drop('tender_lots');
    }
}
