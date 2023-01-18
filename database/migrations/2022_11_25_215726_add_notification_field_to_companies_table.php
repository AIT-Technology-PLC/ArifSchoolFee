<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->integer('expiry_in_days')->nullable()->after('can_check_inventory_on_forms')->default(30);
        });
    }

    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('expiry_in_days');
        });
    }
};