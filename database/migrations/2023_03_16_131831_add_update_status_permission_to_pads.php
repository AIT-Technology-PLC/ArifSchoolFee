<?php

use App\Models\Pad;
use App\Models\PadPermission;
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
        DB::transaction(
            fn() => Pad::query()
                ->whereHas('padStatuses')
                ->whereNotIn('id', PadPermission::where('name', 'Update Status')->pluck('pad_id'))
                ->get()
                ->each(
                    fn($pad) => $pad->padPermissions()->create(['name' => 'Update Status'])
                )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
