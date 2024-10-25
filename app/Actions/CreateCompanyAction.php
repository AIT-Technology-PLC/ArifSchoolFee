<?php

namespace App\Actions;

use App\Actions\CreateUserAction;
use App\Models\Company;
use App\Models\Warehouse;

class CreateCompanyAction
{
    private $action;

    public function __construct(CreateUserAction $action)
    {
        $this->action = $action;
    }

    public function execute($data)
    {
        $company = Company::create([
            'name' => $data['company_name'],
            'currency' => 'ETB',
            'enabled' => 0,
            'paid_time_off_amount' => 16,
        ]);

        $subscription = $company->subscriptions()->create($data['subscriptions']);

        $subscription->approve();

        $warehouse = Warehouse::create([
            'company_id' => $company->id,
            'name' => 'Main Branch',
            'location' => 'Unknown',
            'is_sales_store' => 0,
            'is_active' => 1,
        ]);

        $user = $this->action->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'warehouse_id' => $warehouse->id,
            'enabled' => 1,
            'position' => 'AIT Support Team',
            'role' => 'System Manager',
            'gender' => 'male',
            'address' => 'Addis Ababa, Ethiopia',
            'job_type' => 'remote',
            'phone' => '0966020599',
            'paid_time_off_amount' => $company->paid_time_off_amount,
        ]);

        $user->employee->company()->associate($company)->save();

        $warehouse->createdBy()->associate($user)->save();

        $warehouse->updatedBy()->associate($user)->save();

        $this->createCompensations($company);

        $this->createLeaveCategories($company);

        $this->createTax($company);

        return $user;
    }

    private function createCompensations($company)
    {
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
                'is_taxable' => 0,
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
                'can_be_inputted_manually' => 0,
                'percentage' => 25,
                'maximum_amount' => 2200,
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
    }

    private function createLeaveCategories($company)
    {
        $company->leaveCategories()->createMany([
            [
                'name' => 'Sick Leave',
            ],
            [
                'name' => 'Casual Leave',
            ],
            [
                'name' => 'Maternity Leave',
            ],
            [
                'name' => 'Paternity Leave',
            ],
        ]);
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
}
