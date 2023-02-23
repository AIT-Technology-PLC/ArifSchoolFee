<?php

use App\Models\PurchaseDetail;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        PurchaseDetail::whereRelation('purchase', 'type', 'Imported')->where('amount', 0)->update(['amount' => 1]);
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
