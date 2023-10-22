<?php

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
        Schema::table('sivs', function (Blueprint $table) {
            $table->after('code', fn($t) => $t->nullableMorphs('sivable', 'siv'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sivs', function (Blueprint $table) {
            $table->dropColumn(['sivable_id', 'sivable_type']);

        });
    }
};
