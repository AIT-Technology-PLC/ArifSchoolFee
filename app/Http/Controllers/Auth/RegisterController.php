<?php

namespace App\Http\Controllers\Auth;

use App\Actions\CreateUserAction;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Plan;
use App\Models\Warehouse;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
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
                'plan_id' => Plan::firstWhere('name', 'premium')->id,
            ]);

            $warehouse = Warehouse::create([
                'company_id' => $company->id,
                'name' => 'Main Warehouse',
                'location' => 'Unknown',
                'is_sales_store' => 0,
                'is_active' => 1,
            ]);

            $request = new Request([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'warehouse_id' => $warehouse->id,
                'enabled' => 1,
                'position' => 'Onrica Support Department',
                'role' => 'System Manager',
            ]);

            $user = $this->action->execute($request);

            $user->employee->company()->associate($company)->save();

            $warehouse->createdBy()->associate($user)->save();

            $warehouse->updatedBy()->associate($user)->save();

            return $user;
        });
    }
}
