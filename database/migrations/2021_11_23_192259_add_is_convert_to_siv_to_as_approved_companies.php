<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddIsConvertToSivToAsApprovedCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->boolean('is_convert_to_siv_as_approved')->nullable()->after('is_discount_before_vat');
        });

        DB::statement('UPDATE companies SET is_convert_to_siv_as_approved = 1');

        Schema::table('companies', function (Blueprint $table) {
            $table->boolean('is_convert_to_siv_as_approved')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['is_convert_to_siv_as_approved']);
        });
    }
}
