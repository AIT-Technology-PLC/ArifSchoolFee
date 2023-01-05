<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('companies', function ($table) {
            $table->boolean('can_show_employee_job_title_on_print')->default(1)->after('can_check_inventory_on_forms');
        });
    }

    public function down()
    {
        Schema::table('companies', function ($table) {
            $table->dropColumn('can_show_employee_job_title_on_print');
        });
    }
};
