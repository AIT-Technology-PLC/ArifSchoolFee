<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssignedToToTenderChecklists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tender_checklists', function (Blueprint $table) {
            $table->foreignId('assigned_to')
                ->nullable()
                ->after('general_tender_checklist_id')
                ->constrained('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tender_checklists', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->dropColumn(['assigned_to']);
        });
    }
}
