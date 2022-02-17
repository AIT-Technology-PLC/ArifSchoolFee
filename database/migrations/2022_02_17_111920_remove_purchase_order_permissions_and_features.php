<?php

use App\Models\Feature;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

class RemovePurchaseOrderPermissionsAndFeatures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Permission::where('name', 'like', '%po%')->forceDelete();
        Feature::where('name', 'purchase order')->forceDelete();
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
}
