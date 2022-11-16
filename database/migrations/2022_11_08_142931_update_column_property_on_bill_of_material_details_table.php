<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bill_of_material_details', function (Blueprint $table) {
            $table->decimal('quantity', 22)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('bill_of_material_details', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }
};
