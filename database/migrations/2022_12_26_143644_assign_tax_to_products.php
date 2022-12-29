<?php

use App\Models\Company;
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
            $companies = Company::all();

            foreach ($companies as $company) {
                $this->createTax($company);

                DB::table('products')
                    ->where('company_id', $company->id)
                    ->update(['tax_id' => $company->taxes()->where('type', 'VAT')->first()->id]);
            }
        });
    }

    private function createTax($company)
    {
        $company->taxes()->createMany([
            [
                'type' => 'VAT',
                'amount' => '0.15',
            ],
            [
                'type' => 'TOT2',
                'amount' => '0.02',
            ],
            [
                'type' => 'TOT5',
                'amount' => '0.05',
            ],
            [
                'type' => 'TOT10',
                'amount' => '0.10',
            ],
            [
                'type' => 'NONE',
                'amount' => '0',
            ],

        ]);
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
};
