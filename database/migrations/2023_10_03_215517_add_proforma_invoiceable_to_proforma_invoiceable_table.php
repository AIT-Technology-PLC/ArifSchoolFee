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
        Schema::table('proforma_invoices', function (Blueprint $table) {
            $table->after('code', fn($t) => $t->nullableMorphs('proforma_invoiceable', 'pi'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proforma_invoices', function (Blueprint $table) {
            $table->dropColumn(['proforma_invoiceable_id', 'proforma_invoiceable_type']);
        });
    }
};
