<?php

use App\Models\Gdn;
use App\Models\Sale;
use App\Models\Siv;
use App\Models\Transfer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        ini_set('max_execution_time', '-1');

        DB::transaction(function () {
            Siv::with(['approvedBy', 'createdBy'])->whereNotNull('purpose')->whereNotNull('ref_num')->whereNot('purpose', 'Expo')->get()->each(function ($siv) {
                $warehouseId = $siv->company->isConvertToSivAsApproved() && $siv->isApproved() ? $siv->approvedBy->warehouse_id : $siv->createdBy->warehouse_id;

                $isFound = false;

                if (!$isFound && $siv->purpose == 'DO' && Gdn::where('warehouse_id', $warehouseId)->where('code', $siv->ref_num)->exists()) {
                    $siv->sivable_type = Gdn::class;
                    $siv->sivable_id = Gdn::where('warehouse_id', $warehouseId)->where('code', $siv->ref_num)->first()->id;
                    $siv->description = $siv->description . ' ' . $siv->purpose . ': ' . $siv->ref_num;
                    $siv->save();
                    $isFound = true;
                }

                if (!$isFound && $siv->purpose == 'Invoice' && Sale::where('warehouse_id', $warehouseId)->where('code', $siv->ref_num)->exists()) {
                    $siv->sivable_type = Sale::class;
                    $siv->sivable_id = Sale::where('warehouse_id', $warehouseId)->where('code', $siv->ref_num)->first()->id;
                    $siv->description = $siv->description . ' ' . $siv->purpose . ': ' . $siv->ref_num;
                    $siv->save();
                    $isFound = true;
                }

                if (!$isFound && $siv->purpose == 'Transfer' && Transfer::where('warehouse_id', $warehouseId)->where('code', $siv->ref_num)->exists()) {
                    $siv->sivable_type = Transfer::class;
                    $siv->sivable_id = Transfer::where('warehouse_id', $warehouseId)->where('code', $siv->ref_num)->first()->id;
                    $siv->description = $siv->description . ' ' . $siv->purpose . ': ' . $siv->ref_num;
                    $siv->save();
                    $isFound = true;
                }

                if (!$isFound) {
                    $siv->description = $siv->description . ' ' . $siv->purpose . ': ' . $siv->ref_num;
                    $siv->save();
                }
            });
        });

        Schema::table('sivs', function (Blueprint $table) {
            $table->dropColumn(['purpose', 'ref_num']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sivs', function (Blueprint $table) {
            $table->string('purpose')->nullable();
            $table->bigInteger('ref_num')->nullable();
        });

        Siv::query()->whereHasMorph('sivable', [Gdn::class, Sale::class, Transfer::class])->get()->each(function ($siv) {
            if ($siv->sivable_type == Gdn::class) {
                $siv->purpose = 'DO';
            }

            if ($siv->sivable_type == Sale::class) {
                $siv->purpose = 'Invoice';
            }

            if ($siv->sivable_type == Transfer::class) {
                $siv->purpose = 'Transfer';
            }

            $siv->ref_num = $siv->sivable->code;

            $siv->save();
        });
    }
};
