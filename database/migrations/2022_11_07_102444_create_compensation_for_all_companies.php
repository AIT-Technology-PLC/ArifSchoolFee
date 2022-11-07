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
            Company::all()->each(function ($company) {
                $company->compensations()->forceDelete();

                $basicSalaryCompensation = $company->compensations()->create([
                    'name' => 'Basic Salary',
                    'type' => 'earning',
                    'is_active' => 1,
                    'is_taxable' => 1,
                    'is_adjustable' => 0,
                    'can_be_inputted_manually' => 1,
                ]);

                $company->compensations()->createMany([
                    [
                        'name' => 'Employer Pension Contribution',
                        'depends_on' => $basicSalaryCompensation->id,
                        'type' => 'earning',
                        'is_active' => 1,
                        'is_taxable' => 1,
                        'is_adjustable' => 0,
                        'can_be_inputted_manually' => 0,
                        'percentage' => 11,
                    ],
                    [
                        'name' => 'Overtime',
                        'type' => 'earning',
                        'is_active' => 1,
                        'is_taxable' => 1,
                        'is_adjustable' => 1,
                        'can_be_inputted_manually' => 1,
                    ],
                    [
                        'name' => 'Taxable Transportation Allowance',
                        'type' => 'earning',
                        'is_active' => 1,
                        'is_taxable' => 1,
                        'is_adjustable' => 0,
                        'can_be_inputted_manually' => 1,
                    ],
                    [
                        'name' => 'Non-Taxable Transportation Allowance',
                        'depends_on' => $basicSalaryCompensation->id,
                        'type' => 'earning',
                        'is_active' => 1,
                        'is_taxable' => 0,
                        'is_adjustable' => 0,
                        'can_be_inputted_manually' => 1,
                        'percentage' => 25,
                        'maximum_amount' => 2250,
                    ],
                    [
                        'name' => 'Pension Contribution',
                        'depends_on' => $basicSalaryCompensation->id,
                        'type' => 'deduction',
                        'is_active' => 1,
                        'is_taxable' => 0,
                        'is_adjustable' => 0,
                        'can_be_inputted_manually' => 0,
                        'percentage' => 18,
                    ],
                ]);
            });
        });
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
