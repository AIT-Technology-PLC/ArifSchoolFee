<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customers', function ($table) {
            $table->string('business_license_attachment')->nullable()->after('credit_amount_limit');
            $table->date('business_license_expires_on')->nullable()->after('business_license_attachment');
        });

        Schema::table('suppliers', function ($table) {
            $table->string('business_license_attachment')->nullable()->after('debt_amount_limit');
            $table->date('business_license_expires_on')->nullable()->after('business_license_attachment');
        });
    }

    public function down()
    {
        Schema::table('customers', function ($table) {
            $table->dropColumn('business_license_attachment');
            $table->dropColumn('business_license_expires_on');
        });

        Schema::table('suppliers', function ($table) {
            $table->dropColumn('business_license_attachment');
            $table->dropColumn('business_license_expires_on');
        });
    }
};
