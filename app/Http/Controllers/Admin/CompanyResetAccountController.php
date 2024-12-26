<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CompanyResetAccountRequest;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class CompanyResetAccountController extends Controller
{
    public function __invoke(CompanyResetAccountRequest $request, Company $school)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Resets'), 403);

        if (!$school->isInTraining()) {
            return back()->with('failedMessage', 'Account is in LIVE mode and can not be reset.');
        }

        DB::transaction(function () use ($request, $school) {
            if ($request->validated('reset_master')) {
                $this->resetMasterData($school);
            }

            if ($request->validated('reset_transaction')) {
                $this->resetTransaction($school);
            }

            $this->resetByTables($school, $request->validated('tables'));
        });

        return back()->with('successMessage', 'Account has been resetted as ordered.');
    }

    private function resetMasterData($school)
    {
        $school->academicYears()->forceDelete();

        $school->sections()->forceDelete();

        $school->schoolClasses()->forceDelete();

        $school->vehicles()->forceDelete();

        $school->routes()->forceDelete();

        $school->studentCategories()->forceDelete();

        $school->studentGroups()->forceDelete();

        $school->students()->forceDelete();

        $school->designations()->forceDelete();

        $school->departments()->forceDelete();

        $school->staffs()->forceDelete();

        $school->accounts()->forceDelete();

        $school->feeGroups()->forceDelete();

        $school->feeTypes()->forceDelete();

        $school->feeDiscounts()->forceDelete();

        $school->feeMasters()->forceDelete();
    }

    private function resetTransaction($school)
    {
        $school->userLogs()->forceDelete();

        $school->assignFeeMastsers()->forceDelete();

        $school->assignFeeDiscounts()->forceDelete();

        $school->feePayments()->forceDelete();

        $school->messages()->forceDelete();

        $school->notices()->forceDelete();
    }

    private function resetByTables($school, $tables)
    {
        if (!is_array($tables)) {
            return;
        }

        foreach ($tables as $table) {
            DB::table($table)->where('company_id', $school->id)->delete();
        }
    }
}
