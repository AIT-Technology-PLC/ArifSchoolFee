<?php

use App\Models\Pad;
use Illuminate\Database\Migrations\Migration;
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
        DB::transaction(function () {
            $pads = Pad::where('inventory_operation_type', 'add')->get();

            foreach ($pads as $pad) {
                $pad->padFields()->createMany([
                    [
                        'label' => 'Batch No',
                        'icon' => 'fas fa-th',
                        'is_master_field' => 0,
                        'is_required' => 0,
                        'is_visible' => 0,
                        'is_printable' => 1,
                        'is_readonly' => 0,
                        'tag' => 'input',
                        'tag_type' => 'text',
                    ],
                    [
                        'label' => 'Expires On',
                        'icon' => 'fas fa-calendar-alt',
                        'is_master_field' => 0,
                        'is_required' => 0,
                        'is_visible' => 0,
                        'is_printable' => 1,
                        'is_readonly' => 0,
                        'tag' => 'input',
                        'tag_type' => 'text',
                    ],
                ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batching_fields_for_existing_pads');
    }
};
