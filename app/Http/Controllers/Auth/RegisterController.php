<?php

namespace App\Http\Controllers\Auth;

use App\Actions\CreateUserAction;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Plan;
use App\Models\Warehouse;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    private $action;

    public function __construct(CreateUserAction $action)
    {
        $this->action = $action;

        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'company_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $company = Company::create([
                'name' => $data['company_name'],
                'currency' => 'ETB',
                'enabled' => 0,
                'plan_id' => Plan::firstWhere('name', 'professional')->id,
            ]);

            $warehouse = Warehouse::create([
                'company_id' => $company->id,
                'name' => 'Main Warehouse',
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
                'position' => 'Onrica Support Department',
                'role' => 'System Manager',
                'gender' => 'female',
                'address' => 'Gerji, Taxi Tera',
                'job_type' => 'remote',
                'phone' => '0976006522',
            ]);

            $user->employee->company()->associate($company)->save();

            $warehouse->createdBy()->associate($user)->save();

            $warehouse->updatedBy()->associate($user)->save();

            $this->createCompensations($company);

            return $user;
        });
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
    }
}
