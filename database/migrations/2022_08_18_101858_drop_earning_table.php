<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::drop('earning_details');
        Schema::drop('earnings');
        Schema::drop('earning_categories');
    }

    public function down()
    {
        //
    }
};