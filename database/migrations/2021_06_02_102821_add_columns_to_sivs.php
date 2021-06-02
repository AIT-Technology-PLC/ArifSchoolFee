<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSivs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sivs', function (Blueprint $table) {
            $table->string('purpose')->nullable()->after('code');
            $table->string('ref_num')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sivs', function (Blueprint $table) {
            $table->dropColumn(['type', 'ref_num']);
        });
    }
}
