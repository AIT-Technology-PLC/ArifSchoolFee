<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateSpatiePermissionColumnValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            $str = "App\Models\User";

            DB::table('model_has_permissions')->update([
                'model_type' => $str,
            ]);

            DB::table('model_has_roles')->update([
                'model_type' => $str,
            ]);
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
}
