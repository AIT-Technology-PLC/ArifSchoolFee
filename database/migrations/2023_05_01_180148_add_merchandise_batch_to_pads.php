<?php

use App\Models\Pad;
use App\Models\PadField;
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
            $pads = Pad::where('inventory_operation_type', '<>', 'add')->get();

            foreach ($pads as $pad) {
                $padField = [
                    'label' => 'Batch',
                    'icon' => 'fas fa-th',
                    'is_master_field' => 0,
                    'is_required' => 0,
                    'is_visible' => 0,
                    'is_printable' => 1,
                    'is_readonly' => 0,
                    'tag' => 'select',
                    'tag_type' => '',
                ];

                $padRelation = [
                    'is_relational_field' => 1,
                    'relationship_type' => 'Belongs To',
                    'model_name' => 'Merchandise Batch',
                    'representative_column' => 'batch_no',
                    'component_name' => 'common.batch-list',
                ];

                $pad->padFields()->create($padField)->padRelation()->create($padRelation);
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
        DB::transaction(function () {
            PadField::where('label', 'Batch')->where('tag', 'select')->whereHas('padRelation')->forceDelete();
        });
    }
};
