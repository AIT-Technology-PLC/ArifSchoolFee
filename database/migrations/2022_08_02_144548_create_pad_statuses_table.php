<?php

use App\Models\TransactionField;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('pad_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pad_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->string('text_color');
            $table->string('bg_color');
            $table->boolean('is_active');
            $table->boolean('is_editable');
            $table->boolean('is_deletable');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->string('status')->nullable()->after('code');
        });

        Schema::table('pads', function (Blueprint $table) {
            $table->dropColumn(['is_closable', 'is_cancellable']);
        });

        DB::transaction(function () {
            TransactionField::where('key', 'closed_by')->get()->each(function ($transactionField) {
                $transactionField->key = '';
                $transactionField->value = '';
                $transactionField->save();

                $transactionField->transaction->status = 'Closed';
                $transactionField->transaction->save();
            });

            TransactionField::where('key', 'cancelled_by')->get()->each(function ($transactionField) {
                $transactionField->key = '';
                $transactionField->value = '';
                $transactionField->save();

                $transactionField->transaction->status = 'Cancelled';
                $transactionField->transaction->save();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pad_statuses');

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
