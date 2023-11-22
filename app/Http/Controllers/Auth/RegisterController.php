<?php

namespace App\Http\Controllers\Auth;

use App\Actions\CreateCompanyAction;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    private $action;

    public function __construct(CreateCompanyAction $action)
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
            $data['subscriptions'] = [
                'plan_id' => Plan::firstWhere('name', 'v3-professional')->id,
                'months' => 12,
            ];

            return $this->action->execute($data);
        });
    }
}
