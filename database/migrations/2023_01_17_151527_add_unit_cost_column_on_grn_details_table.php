<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('grn_details', function ($table) {
            $table->decimal('unit_cost', 22)->default(0.00)->after('quantity');
        });
    }

    public function down()
    {
        Schema::table('grn_details', function ($table) {
            $table->dropColumn('unit_cost');
        });
    }
};
