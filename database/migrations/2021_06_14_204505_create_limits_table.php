<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['description']);
            $table->boolean('is_enabled')->after('name');
        });

        Schema::create('limits', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('limitables', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('limit_id')->nullable()->unsigned();
            $table->bigInteger('limitable_id');
            $table->string('limitable_type');
            $table->bigInteger('amount');
            $table->timestamps();
            $table->softDeletes();

            $table->index('limit_id');

            $table->foreign('limit_id')->references('id')->on('limits')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->longText('description')->nullable()->after('name');
            $table->dropColumn(['is_enabled']);
        });

        Schema::drop('limitables');

        Schema::drop('limits');
    }
}
