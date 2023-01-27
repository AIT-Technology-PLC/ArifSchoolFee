<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('companies', function ($table) {
            $table->boolean('filter_customer_and_supplier')->default(1)->after('can_select_batch_number_on_forms');
        });
    }

    public function down()
    {
        Schema::table('companies', function ($table) {
            $table->dropColumn('filter_customer_and_supplier');
        });
    }
};
