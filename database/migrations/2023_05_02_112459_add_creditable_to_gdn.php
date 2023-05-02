<?php

use App\Models\Credit;
use App\Models\Gdn;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('credits', function (Blueprint $table) {
            $table->after('code', fn($t) => $t->nullableMorphs('creditable'));
        });

        Credit::whereNotNull('gdn_id')->get()->each(function ($credit) {
            $credit->creditable()->associate(Gdn::find($credit->gdn_id));

            $credit->save();
        });

        Schema::table('credits', function (Blueprint $table) {
            $table->dropForeign(['gdn_id']);
            $table->dropColumn('gdn_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credits', function (Blueprint $table) {
            $table->foreignId('gdn_id')->nullable()->references('id')->on('gdns')->cascadeOnDelete()->cascadeOnUpdate();
        });

        Credit::whereNotNull('creditable_id')->get()->each(function ($credit) {
            $credit->gdn_id = $credit->creditable_id;
            $credit->save();
        });

        Schema::table('credits', function (Blueprint $table) {
            $table->dropMorphs('creditable');
        });
    }
};
