<?php

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use App\Models\Limit;
use App\Models\Plan;
use App\Models\SchoolType;
use App\Actions\CreateSchoolAction;
use App\Http\Requests\Admin\StoreCompanyRequest;
use App\Notifications\SubscriptionPrepared;
use Illuminate\Support\Facades\DB;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\Notification;

class CompanyController extends Controller
{
    public function __construct(CreateSchoolAction $action)
    {
        $this->middleware('guest');
    }

    public function register()
    {
        $plans = Plan::enabled()->get();
        $limits = Limit::all();
        $schoolTypes = SchoolType::all();

        return view('schools.register', compact('plans', 'limits','schoolTypes'));
    }

    public function store(StoreCompanyRequest $request, CreateSchoolAction $createCompanyAction)
    {
        try {
            $user = DB::transaction(function () use ($request, $createCompanyAction) {
                $user = $createCompanyAction->execute($request->safe()->except('limit'));
                $user->employee->company->limits()->sync($request->validated('limit'));
                
                return $user;
            });

            $school = $user->employee->company;

            Notification::send(Notifiables::byAdminPermission('Manage Admin Panel Subscriptions'), new SubscriptionPrepared($school));

            return redirect()->back()->with('successMessage', 'Thank you for subscribing! Our admin team will review your school`s subscription and reach out to you shortly.');
        } catch (\Exception $e) {
            return redirect()->back()->with('failedMessage', 'Something went wrong while subscribing. Please try again.');
        }
    }
}
