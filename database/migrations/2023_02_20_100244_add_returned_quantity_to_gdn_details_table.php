<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('gdn_details', function (Blueprint $table) {
            $table->decimal('returned_quantity', 22)->default(0)->after('quantity');
        });
    }

    public function down()
    {
        Schema::table('gdn_details', function (Blueprint $table) {
            $table->dropColumn('returned_quantity');
        });
    }
};