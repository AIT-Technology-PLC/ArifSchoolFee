<?php

use App\Models\Expense;
use App\Models\Tax;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('tax_id')->nullable()->after('supplier_id')->constrained()->onDelete('set null')->onUpdate('cascade');
            $expenses = Expense::get();
            foreach ($expenses as $expense) {
                if ($expense->tax_type == null) {
                    $taxType = Tax::where('type', 'NONE')->first();
                    $expense->tax_id = $taxType->id;
                }

                if ($expense->tax_type != null) {
                    $taxType = Tax::where('type', $expense->tax_type)->first();
                    $expense->tax_id = $taxType->id;
                }

                $expense->save();
            }

            $table->dropColumn('tax_type');
        });
    }

    public function down()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->string('tax_type');
        });
    }
};