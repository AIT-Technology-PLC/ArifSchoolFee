<?php

use App\Models\CustomFieldValue;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('custom_field_values', function (Blueprint $table) {
            $table->foreignId('warehouse_id')->after('custom_field_id')->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
        });

        CustomFieldValue::with('customFieldValuable')->get()->each(function ($customFieldValue) {
            $customFieldValue->warehouse_id = $customFieldValue->customFieldValuable?->warehouse_id;
            $customFieldValue->save();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_field_values', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn('warehouse_id');
        });
    }
};
