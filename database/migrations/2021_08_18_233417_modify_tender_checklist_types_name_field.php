<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTenderChecklistTypesNameField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tender_checklist_types', function (Blueprint $table) {
            $table->dropUnique(['name']);

            $table->unique(['name', 'company_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tender_checklist_types', function (Blueprint $table) {
            $table->dropUnique(['name', 'company_id']);

            $table->unique(['name']);
        });
    }
}
