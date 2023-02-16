<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('returns', function (Blueprint $table) {
            $table->foreignId('gdn_id')->nullable()->after('warehouse_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('returns', function (Blueprint $table) {
            $table->dropForeign('gdn_id');
            $table->dropColumn('gdn_id');
        });
    }
};
