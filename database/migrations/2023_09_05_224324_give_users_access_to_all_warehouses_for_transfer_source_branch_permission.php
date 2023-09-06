<?php

use App\Models\User;
use App\Models\Warehouse;
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
        ini_set('max_execution_time', '-1');

        DB::transaction(function () {
            User::with('warehouse.company')->get()->each(function ($user) {
                $warehouseIds = Warehouse::where('company_id', $user->warehouse->company_id)->pluck('id')->toArray();

                foreach ($warehouseIds as $warehouseId) {
                    DB::table('user_warehouse')
                        ->insert([
                            'user_id' => $user->id,
                            'warehouse_id' => $warehouseId,
                            'type' => 'transfer_source',
                        ]);
                }
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {}
};
