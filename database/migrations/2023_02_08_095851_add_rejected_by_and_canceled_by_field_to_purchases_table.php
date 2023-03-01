<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->foreignId('rejected_by')->nullable()->after('purchased_by')->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('cancelled_by')->nullable()->after('rejected_by')->constrained('users')->onDelete('set null')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign('rejected_by');
            $table->dropColumn('rejected_by');
            $table->dropForeign('cancelled_by');
            $table->dropColumn('cancelled_by');
        });
    }
};