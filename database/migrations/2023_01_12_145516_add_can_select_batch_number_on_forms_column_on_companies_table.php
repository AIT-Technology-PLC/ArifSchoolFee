<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('companies', function ($table) {
            $table->boolean('can_select_batch_number_on_forms')->default(0)->after('can_show_employee_job_title_on_print');
        });
    }

    public function down()
    {
        Schema::table('companies', function ($table) {
            $table->dropColumn('can_select_batch_number_on_forms');
        });
    }
};
