<?php

use App\Models\Purchase;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovedByToPurchases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tender_checklists', function (Blueprint $table) {
            $table->longText('comment')->nullable()->change();
        });
        
        Schema::table('purchases', function (Blueprint $table) {
            $table->foreignId('approved_by')
                ->nullable()
                ->after('updated_by')
                ->constrained('users')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->foreignId('purchased_by')
                ->nullable()
                ->after('approved_by')
                ->constrained('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });

        Purchase::all()->each(function ($purchase) {
            if ($purchase->grns()->exists()) {
                $purchase->approved_by = $purchase->updated_by;
                $purchase->purchased_by = $purchase->updated_by;

                $purchase->save();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropConstrainedForeignId('approved_by');
            $table->dropConstrainedForeignId('purchased_by');
        });
    }
}
