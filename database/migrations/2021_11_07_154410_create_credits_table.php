<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('gdn_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->bigInteger('code');
            $table->decimal('cash_amount', 22);
            $table->decimal('credit_amount', 22);
            $table->decimal('credit_amount_settled', 22);
            $table->longText('description')->nullable();
            $table->dateTime('issued_on')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->dateTime('last_settled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('warehouse_id');
            $table->index('customer_id');
            $table->unique(['company_id', 'warehouse_id', 'code']);
        });

        Schema::create('credit_settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('amount', 22);
            $table->string('method');
            $table->string('reference_number')->nullable();
            $table->dateTime('settled_at')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('credit_id');
        });

        // GDNS
        Schema::table('gdns', function (Blueprint $table) {
            $table->dateTime('due_date')->nullable()->after('issued_on');
        });

        DB::statement('
            UPDATE gdns
            SET due_date = DATE_ADD(issued_on, INTERVAL 10 DAY)
            WHERE payment_type = "Credit Payment" || cash_received_in_percentage < 100
        ');

        // CUSTOMERS
        Schema::table('customers', function (Blueprint $table) {
            $table->decimal('credit_amount_limit', 22)->nullable()->after('country');
        });

        DB::statement('
            UPDATE customers
            SET credit_amount_limit = 0.00
        ');

        Schema::table('customers', function (Blueprint $table) {
            $table->decimal('credit_amount_limit', 22)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('credit_settlements');
        Schema::drop('credits');

        Schema::table('gdns', function (Blueprint $table) {
            $table->dropColumn('due_date');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('credit_amount_limit');
        });
    }
}
