<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('expiry_time_type')->nullable()->after('can_check_inventory_on_forms')->default("Months");
            $table->integer('expired_in')->nullable()->after('expiry_time_type')->default(3);
        });
    }

    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('expiry_time_type');
            $table->dropColumn('expired_in');
        });
    }
};