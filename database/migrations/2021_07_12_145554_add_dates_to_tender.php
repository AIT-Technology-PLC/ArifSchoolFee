<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatesToTender extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenders', function (Blueprint $table) {
            $table->dateTime('clarify_on')->nullable();
            $table->dateTime('visit_on')->nullable();
            $table->dateTime('premeet_on')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenders', function (Blueprint $table) {
            $table->dropColumn(['clarify_on', 'visit_on', 'premeet_on']);
        });
    }
}
