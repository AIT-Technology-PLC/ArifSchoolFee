<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('featurables', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('feature_id')->nullable()->unsigned();
            $table->bigInteger('featurable_id');
            $table->string('featurable_type');

            $table->unique(['feature_id', 'featurable_id', 'featurable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('featurables');
        Schema::drop('features');
    }
}
