<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\WarningDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWarningRequest;
use App\Http\Requests\UpdateWarningRequest;
use App\Models\User;
use App\Models\Warning;
use Illuminate\Support\Facades\DB;

class WarningController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Warning Management');

        $this->authorizeResource(Warning::class, 'warning');
    }

    public function index(WarningDatatable $datatable)
    {
        $datatable->builder()->setTableId('warnings-datatable')->orderBy(1, 'asc');

        $totalWarnings = Warning::count();

        $totalInitalWarning = Warning::initial()->count();

        $totalAffirmedWarning = Warning::affirmation()->count();

        $totalFinalWarning = Warning::final ()->count();

        return $datatable->render('warnings.index', compact('totalWarnings', 'totalInitalWarning', 'totalAffirmedWarning', 'totalFinalWarning'));
    }

    public function create()
    {
        $users = User::getUsers(false);

        return view('warnings.create', compact('users'));
    }

    public function store(StoreWarningRequest $request)
    {
        $warnings = collect($request->validated('warning'));

        DB::transaction(function () use ($warnings) {
            foreach ($warnings as $warning) {
                Warning::create($warning);
            }
        });

        return redirect()->route('warnings.index');
    }

    public function show(Warning $warning)
    {
        $warning->load('employee.user');

        return view('warnings.show', compact('warning'));
    }

    public function edit(Warning $warning)
    {
        if ($warning->isApproved()) {
            return back()->with('failedMessage', 'You can not modify a warning request that is approved.');
        }

        $warning->load('employee.user');

        return view('warnings.edit', compact('warning'));
    }

    public function update(UpdateWarningRequest $request, Warning $warning)
    {
        if ($warning->isApproved()) {
            return back()->with('failedMessage', 'You can not modify a warning request that is approved.');
        }

        $warning->update($request->validated());

        return redirect()->route('warnings.show', $warning->id);
    }

    public function destroy(Warning $warning)
    {
        if ($warning->isApproved()) {
            return back()->with('failedMessage', 'You can not delete a warning request that is approved.');
        }

        $warning->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
