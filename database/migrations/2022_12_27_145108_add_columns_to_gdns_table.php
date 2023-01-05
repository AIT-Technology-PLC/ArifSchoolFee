<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('gdns', function (Blueprint $table) {
            $table->foreignId('added_by')->nullable()->after('subtracted_by')->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('cancelled_by')->nullable()->after('added_by')->constrained('users')->onDelete('set null')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('gdns', function (Blueprint $table) {
            $table->dropForeign('added_by');
            $table->dropColumn('added_by');
            $table->dropForeign('cancelled_by');
            $table->dropColumn('cancelled_by');
        });
    }
};
