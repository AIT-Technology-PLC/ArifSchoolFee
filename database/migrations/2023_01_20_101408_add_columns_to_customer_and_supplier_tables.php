<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customers', function ($table) {
            $table->string('business_licence')->nullable()->after('credit_amount_limit');
            $table->date('document_expire_on')->nullable()->after('business_licence');
        });

        Schema::table('suppliers', function ($table) {
            $table->string('business_licence')->nullable()->after('debt_amount_limit');
            $table->date('document_expire_on')->nullable()->after('business_licence');
        });
    }

    public function down()
    {
        Schema::table('customers', function ($table) {
            $table->dropColumn('business_licence');
            $table->dropColumn('document_expire_on');
        });

        Schema::table('suppliers', function ($table) {
            $table->dropColumn('business_licence');
            $table->dropColumn('document_expire_on');
        });
    }
};
