<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->boolean('enabled_commission_setting')->default(0)->after('is_in_training');
            $table->string('charge_from')->default('payer')->after('enabled_commission_setting');
            $table->string('charge_type')->default('amount')->after('charge_from');
            $table->decimal('charge_amount', 22)->default(0.00)->after('charge_type');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('enabled_commission_setting');
            $table->dropColumn('charge_from');
            $table->dropColumn('charge_type');
            $table->dropColumn('charge_amount');
        });
    }
};
