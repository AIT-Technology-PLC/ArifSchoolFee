<?php

use App\Models\TransactionField;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            $transactions = TransactionField::query()
                ->with('transaction')
                ->where('key', 'subtracted_by')
                ->whereNull('line')
                ->get()
                ->pluck('transaction')
                ->unique();

            $transactions->each(function ($transaction) {
                $lines = $transaction->transactionFields->whereNotNull('line')->pluck('line')->unique()->values();

                foreach ($lines as $line) {
                    $transaction->transactionFields()->create([
                        'key' => 'subtracted_by',
                        'value' => $transaction->transactionFields->firstWhere('key', 'subtracted_by')->value,
                        'line' => $line,
                    ]);
                }
            });

            $transactions = TransactionField::query()
                ->with('transaction')
                ->where('key', 'added_by')
                ->whereNull('line')
                ->get()
                ->pluck('transaction')
                ->unique();

            $transactions->each(function ($transaction) {
                $lines = $transaction->transactionFields->whereNotNull('line')->pluck('line')->unique()->values();

                foreach ($lines as $line) {
                    $transaction->transactionFields()->create([
                        'key' => 'added_by',
                        'value' => $transaction->transactionFields->firstWhere('key', 'added_by')->value,
                        'line' => $line,
                    ]);
                }
            });

            TransactionField::with('transaction')->where('key', 'subtracted_by')->whereNull('line')->forceDelete();
            TransactionField::with('transaction')->where('key', 'added_by')->whereNull('line')->forceDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
