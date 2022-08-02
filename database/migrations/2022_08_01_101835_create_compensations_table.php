<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('compensations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('name');
            $table->string('type');
            $table->boolean('is_taxable');
            $table->boolean('is_adjustable');
            $table->boolean('can_be_inputted_manually');
            $table->decimal('percentage', 22)->nullable();
            $table->decimal('default_value', 22)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::table('compensations', function (Blueprint $table) {
            $table->foreignId('depends_on')->nullable()->after('id')->constrained('compensations')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::drop('compensations');
    }
};