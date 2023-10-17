<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Warehouse;
use Illuminate\Console\Command;

class TempCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:temp-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $transactions = Transaction::where('pad_id', 45)->where('created_by', 771)->orderBy('issued_on')->get();
        $rows = [];

        foreach ($transactions as $transaction) {
            foreach ($transaction->transactionDetails as $detail) {
                $rows[] = [
                    'issued_on' => $transaction->issued_on->toDateString(),
                    'product' => Product::find($detail['product_id'])->name,
                    'unit' => $detail['unit'],
                    'quantity' => $detail['quantity'],
                    'prepared_by' => $$transaction->createdBy->name,
                    'warehouse' => Warehouse::find($detail['warehouse_id'])->name,
                ];
            }
        }

        $this->table(['issued_on', 'product', 'unit', 'quantity', 'prepared_by', 'warehouse'], $rows);
    }
}
