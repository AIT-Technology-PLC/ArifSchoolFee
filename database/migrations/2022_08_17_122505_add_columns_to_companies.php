<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('print_template_image')->nullable();
            $table->decimal('print_padding_top')->default(10);
            $table->decimal('print_padding_bottom')->default(10);
            $table->decimal('print_padding_horizontal')->default(5);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['print_template_image', 'print_padding_top', 'print_padding_bottom', 'print_padding_horizontal']);
        });
    }
};
