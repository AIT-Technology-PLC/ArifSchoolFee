<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bill_of_materials', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade')->after('company_id');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade')->after('updated_by');
        });
    }

    public function down()
    {
        Schema::table('bill_of_materials', function (Blueprint $table) {
            $table->dropColumn('customer_id');
            $table->dropColumn('approved_by');
        });
    }
};