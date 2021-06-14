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
            $table->boolean('is_enabled');
        });

        Schema::create('limits', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('plan_id')->nullable()->unsigned();
            $table->string('name');
            $table->bigInteger('value');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['plan_id', 'name']);

            $table->index('plan_id');

            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('company_limit', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('limit_id')->unsigned();
            $table->bigInteger('value');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'limit_id']);

            $table->index('company_id');

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
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
            $table->longText('description')->nullable();
            $table->dropColumn(['is_enabled']);
        });

        Schema::drop('company_limit');

        Schema::drop('limits');
    }
}
