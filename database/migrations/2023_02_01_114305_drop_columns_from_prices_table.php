<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('prices', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('min_price');
            $table->dropColumn('max_price');

            $table->decimal('fixed_price', 22)->change();

            $table->dropForeign(['product_id']);
            $table->dropUnique(['product_id']);
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');

            $table->string('name')->nullable()->after('fixed_price');
            $table->boolean('is_active')->default(1)->after('name');
        });
    }

    public function down()
    {
        Schema::table('prices', function (Blueprint $table) {
            $table->string('type');
            $table->decimal('min_price', 22)->nullable();
            $table->decimal('max_price', 22)->nullable();

            $table->decimal('fixed_price', 22)->nullable()->change();

            $table->unique('product_id');

            $table->dropColumn('name');
            $table->dropColumn('is_active');
        });
    }
};
