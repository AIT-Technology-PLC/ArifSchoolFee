<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ModifyCodeColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            Schema::table('adjustments', function (Blueprint $table) {
                $table->dropUnique(['code']);
            });

            DB::statement('update adjustments set code = SUBSTR(code, POSITION("_" IN code) + 1)');

            Schema::table('adjustments', function (Blueprint $table) {
                $table->bigInteger('code')->change();

                $table->unique(['company_id', 'warehouse_id', 'code']);
            });
        });

        DB::transaction(function () {
            Schema::table('damages', function (Blueprint $table) {
                $table->dropUnique(['code']);
            });

            DB::statement('update damages set code = SUBSTR(code, POSITION("_" IN code) + 1)');

            Schema::table('damages', function (Blueprint $table) {
                $table->bigInteger('code')->change();

                $table->unique(['company_id', 'warehouse_id', 'code']);
            });
        });

        DB::transaction(function () {
            Schema::table('grns', function (Blueprint $table) {
                $table->dropUnique(['code']);
            });

            DB::statement('update grns set code = SUBSTR(code, POSITION("_" IN code) + 1)');

            Schema::table('grns', function (Blueprint $table) {
                $table->bigInteger('code')->change();

                $table->unique(['company_id', 'warehouse_id', 'code']);
            });
        });

        DB::transaction(function () {
            Schema::table('proforma_invoices', function (Blueprint $table) {
                $table->dropUnique(['code']);
            });

            DB::statement('update proforma_invoices set code = SUBSTR(code, POSITION("_" IN code) + 1)');

            Schema::table('proforma_invoices', function (Blueprint $table) {
                $table->bigInteger('code')->change();

                $table->unique(['company_id', 'warehouse_id', 'code']);
            });
        });

        DB::transaction(function () {
            Schema::table('purchases', function (Blueprint $table) {
                $table->renameIndex('purchases_purchase_no_unqiue', 'purchases_purchase_no_unique');
                $table->dropUnique(['purchase_no']);
                $table->renameColumn('purchase_no', 'code');
            });

            DB::statement('update purchases set code = SUBSTR(code, POSITION("_" IN code) + 1)');

            Schema::table('purchases', function (Blueprint $table) {
                $table->bigInteger('code')->change();

                $table->unique(['company_id', 'warehouse_id', 'code']);
            });
        });

        DB::transaction(function () {
            Schema::table('reservations', function (Blueprint $table) {
                $table->dropUnique(['code']);
            });

            DB::statement('update reservations set code = SUBSTR(code, POSITION("_" IN code) + 1)');

            Schema::table('reservations', function (Blueprint $table) {
                $table->bigInteger('code')->change();

                $table->unique(['company_id', 'warehouse_id', 'code']);
            });
        });

        DB::transaction(function () {
            Schema::table('returns', function (Blueprint $table) {
                $table->dropUnique(['code']);
            });

            DB::statement('update returns set code = SUBSTR(code, POSITION("_" IN code) + 1)');

            Schema::table('returns', function (Blueprint $table) {
                $table->bigInteger('code')->change();

                $table->unique(['company_id', 'warehouse_id', 'code']);
            });
        });

        DB::transaction(function () {
            Schema::table('sales', function (Blueprint $table) {
                $table->dropUnique(['receipt_no']);
                $table->renameColumn('receipt_no', 'code');
            });

            DB::statement('update sales set code = SUBSTR(code, POSITION("_" IN code) + 1)');

            Schema::table('sales', function (Blueprint $table) {
                $table->bigInteger('code')->change();

                $table->unique(['company_id', 'warehouse_id', 'code']);
            });
        });

        DB::transaction(function () {
            Schema::table('sivs', function (Blueprint $table) {
                $table->dropUnique(['code']);
            });

            DB::statement('update sivs set code = SUBSTR(code, POSITION("_" IN code) + 1)');

            Schema::table('sivs', function (Blueprint $table) {
                $table->bigInteger('code')->change();

                $table->unique(['company_id', 'warehouse_id', 'code']);
            });
        });

        DB::transaction(function () {
            Schema::table('transfers', function (Blueprint $table) {
                $table->dropUnique(['code']);
            });

            DB::statement('update transfers set code = SUBSTR(code, POSITION("_" IN code) + 1)');

            Schema::table('transfers', function (Blueprint $table) {
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
