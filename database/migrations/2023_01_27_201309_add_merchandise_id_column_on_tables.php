<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('proforma_invoice_details', function (Blueprint $table) {
            $table->foreignId('merchandise_batch_id')->nullable()->after('product_id')->constrained()->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('transfer_details', function (Blueprint $table) {
            $table->foreignId('merchandise_batch_id')->nullable()->after('product_id')->constrained()->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('return_details', function (Blueprint $table) {
            $table->foreignId('merchandise_batch_id')->nullable()->after('product_id')->constrained()->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('adjustment_details', function (Blueprint $table) {
            $table->foreignId('merchandise_batch_id')->nullable()->after('product_id')->constrained()->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('reservation_details', function (Blueprint $table) {
            $table->foreignId('merchandise_batch_id')->nullable()->after('product_id')->constrained()->onDelete('set null')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('proforma_invoice_details', function (Blueprint $table) {
            $table->dropForeign('merchandise_batch_id');
            $table->dropColumn('merchandise_batch_id');
        });

        Schema::table('transfer_details', function (Blueprint $table) {
            $table->dropForeign('merchandise_batch_id');
            $table->dropColumn('merchandise_batch_id');
        });

        Schema::table('return_details', function (Blueprint $table) {
            $table->dropForeign('merchandise_batch_id');
            $table->dropColumn('merchandise_batch_id');
        });

        Schema::table('adjustment_details', function (Blueprint $table) {
            $table->dropForeign('merchandise_batch_id');
            $table->dropColumn('merchandise_batch_id');
        });

        Schema::table('reservation_details', function (Blueprint $table) {
            $table->dropForeign('merchandise_batch_id');
            $table->dropColumn('merchandise_batch_id');
        });
    }
};
