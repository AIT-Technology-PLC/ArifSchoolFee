<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::table('sales', function (Blueprint $table) {
            $table->string('cash_received_type')->after('payment_type');
            $table->decimal('cash_received', 22)->after('cash_received_type');

            $table->renameColumn('sold_on', 'issued_on');
            $table->dropColumn('discount');
        });

        Schema::table('sale_details', function (Blueprint $table) {
            $table->longText('description')->nullable()->after('unit_price');

            $table->dropConstrainedForeignId('warehouse_id');
            $table->dropColumn('discount');
        });

        DB::table('sales')->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['cash_received_type', 'cash_received']);

            $table->renameColumn('issued_on', 'sold_on');
            $table->decimal('discount', 22)->nullable();
        });

        Schema::table('sale_details', function (Blueprint $table) {
            $table->dropColumn('description');

            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('discount', 22)->nullable();
        });
    }
};
