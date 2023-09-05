<?php

use App\Actions\SyncWarehousePermissionsAction;
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

                (new SyncWarehousePermissionsAction)->execute($user, ['transfer_source' => $warehouseIds]);
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
