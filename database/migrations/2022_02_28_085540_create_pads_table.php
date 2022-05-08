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
        Schema::create('pads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('name');
            $table->string('abbreviation');
            $table->string('icon');
            $table->string('inventory_operation_type'); // add, subtract, none
            $table->boolean('is_approvable');
            $table->boolean('is_closable');
            $table->boolean('is_cancellable');
            $table->boolean('is_printable');
            $table->boolean('has_prices');
            $table->boolean('has_payment_term');
            $table->boolean('is_enabled');
            $table->string('module');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pad_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pad_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            // for prices there should be quantity, unit price, discount (master = true), discount (master = false)
            $table->string('label');
            $table->string('icon');
            $table->boolean('is_master_field');
            $table->boolean('is_required');
            $table->boolean('is_visible');
            $table->boolean('is_printable');
            $table->string('tag');
            $table->string('tag_type')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pad_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pad_field_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('relationship_type');
            $table->string('model_name');
            $table->string('representative_column');
            $table->string('component_name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pad_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pad_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['pad_id', 'name']);
        });

        Schema::create('pad_permissions_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pad_permission_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['pad_permission_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pad_permissions_users');
        Schema::drop('pad_permissions');
        Schema::drop('pad_relations');
        Schema::drop('pad_fields');
        Schema::drop('pads');
    }
};
