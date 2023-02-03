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
            $table->dropUnique('product_id');

            $table->string('price_tag')->nullable()->after('fixed_price');
            $table->boolean('is_active')->after('price_tag');
        });
    }

    public function down()
    {
        Schema::table('prices', function (Blueprint $table) {
            $table->string('type');
            $table->decimal('min_price', 22)->nullable();
            $table->decimal('max_price', 22)->nullable();

            $table->decimal('fixed_price', 22)->nullable()->change();
            $table->foreignId('product_id')->unique()->constrained()->onDelete('cascade')->onUpdate('cascade')->change();

            $table->dropColumn('price_tag');
            $table->dropColumn('is_active');
        });
    }
};
