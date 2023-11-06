<?php

use App\Models\Gdn;
use App\Models\Sale;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        ini_set('max_execution_time', '-1');

        DB::transaction(function () {
            Gdn::with('gdnDetails')
                ->subtracted()
                ->whereRelation('gdnDetails', 'delivered_quantity', 0)
                ->whereHas('sivs', fn($q) => $q->approved())
                ->get()
                ->each(function ($gdn) {
                    foreach ($gdn->gdnDetails as $gdnDetail) {
                        $gdnDetail->delivered_quantity = $gdn
                            ->sivs()
                            ->with('sivDetails')
                            ->approved()
                            ->get()
                            ->pluck('sivDetails')
                            ->flatten()
                            ->where('product_id', $gdnDetail->product_id)
                            ->where('warehouse_id', $gdnDetail->warehouse_id)
                            ->sum('quantity');

                        $gdnDetail->save();
                    }
                });

            Sale::with('saleDetails')
                ->subtracted()
                ->whereRelation('saleDetails', 'delivered_quantity', 0)
                ->whereHas('sivs', fn($q) => $q->approved())
                ->get()
                ->each(function ($sale) {
                    foreach ($sale->saleDetails as $saleDetail) {
                        $saleDetail->delivered_quantity = $sale
                            ->sivs()
                            ->with('sivDetails')
                            ->approved()
                            ->get()
                            ->pluck('sivDetails')
                            ->flatten()
                            ->where('product_id', $saleDetail->product_id)
                            ->where('warehouse_id', $saleDetail->warehouse_id)
                            ->sum('quantity');

                        $saleDetail->save();
                    }
                });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
